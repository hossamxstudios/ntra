<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MobileDevice;
use App\Models\Mobile;
use App\Models\Passenger;
use App\Models\ImeiCheck;
use App\Models\Payment;
use App\Models\Suggestion;
use App\Models\Complaint;
use App\Services\ImeiApiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PageController extends Controller {

    public function welcomePage(Request $request) {
        $result = Machine::findOrCreateByToken(
            $request->cookie('machine_token'),
            $request->ip(),
            $request->userAgent()
        );
        $machine = $result['machine'];

        $response = response()->view('front.welcome.index', compact('machine'));

        // Set cookie if new machine
        if ($result['isNew']) {
            $response->cookie('machine_token', $machine->machine_token, 60 * 24 * 365); // 1 year
        }

        return $response;
    }

    public function imeiCheck(Request $request) {
        $result = Machine::findOrCreateByToken(
            $request->cookie('machine_token'),
            $request->ip(),
            $request->userAgent()
        );
        $machine = $result['machine'];

        $response = response()->view('front.imei.index', compact('machine'));

        if ($result['isNew']) {
            $response->cookie('machine_token', $machine->machine_token, 60 * 24 * 365);
        }

        return $response;
    }

    public function imeiRegister(MobileDevice $device) {
        $estimatedPrice = 25000;
        $taxAmount = $device->tax ?? ($estimatedPrice * 0.37);

        // Get price from mobiles table if available
        if ($device->brand && $device->model) {
            $mobile = Mobile::findByBrandAndModel($device->brand, $device->model);
            if ($mobile) {
                $estimatedPrice = $mobile->estimated_price;
                $taxAmount = $estimatedPrice * 0.37;
            }
        }

        return view('front.imei.register', compact('device', 'estimatedPrice', 'taxAmount'));
    }

    public function imeiRegisterSubmit(Request $request, MobileDevice $device) {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'passport_no' => 'required|string|max:50',
            'nationality' => 'required|string|max:100',
        ], [
            'first_name.required' => 'يرجى إدخال الاسم الأول',
            'last_name.required' => 'يرجى إدخال اسم العائلة',
            'passport_no.required' => 'يرجى إدخال رقم جواز السفر',
            'nationality.required' => 'يرجى إدخال الجنسية',
        ]);

        // Get machine by token
        $result = Machine::findOrCreateByToken(
            $request->cookie('machine_token'),
            $request->ip(),
            $request->userAgent()
        );
        $machine = $result['machine'];

        // Create or update passenger
        $passenger = Passenger::updateOrCreate(
            ['passport_no' => $request->passport_no],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'nationality' => $request->nationality,
            ]
        );

        // Save scanned images using Spatie Media Library
        try {
            // Passenger photo (data URL format: data:image/jpeg;base64,...)
            if ($request->filled('passenger_photo')) {
                $photoData = $request->passenger_photo;
                if (str_starts_with($photoData, 'data:image')) {
                    $passenger->clearMediaCollection('passenger_photo');
                    $passenger->addMediaFromBase64(preg_replace('/^data:image\/\w+;base64,/', '', $photoData))
                        ->usingFileName('passenger_photo_' . $passenger->id . '.jpg')
                        ->toMediaCollection('passenger_photo');
                }
            }

            // Passport scan (pure base64)
            if ($request->filled('passport_image_base64')) {
                $passenger->clearMediaCollection('passport_document');
                $passenger->addMediaFromBase64($request->passport_image_base64)
                    ->usingFileName('passport_' . $passenger->id . '.jpg')
                    ->toMediaCollection('passport_document');
            }

            // Arrival stamp (pure base64)
            if ($request->filled('arrival_image_base64')) {
                $passenger->clearMediaCollection('arrival_stamp');
                $passenger->addMediaFromBase64($request->arrival_image_base64)
                    ->usingFileName('arrival_stamp_' . $passenger->id . '.jpg')
                    ->toMediaCollection('arrival_stamp');
            }

            // Boarding card (pure base64)
            if ($request->filled('boarding_image_base64')) {
                $passenger->clearMediaCollection('boarding_card');
                $passenger->addMediaFromBase64($request->boarding_image_base64)
                    ->usingFileName('boarding_card_' . $passenger->id . '.jpg')
                    ->toMediaCollection('boarding_card');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to save passenger media: ' . $e->getMessage());
        }

        // Calculate tax
        $estimatedPrice = 25000;
        $taxAmount = $device->tax ?? ($estimatedPrice * 0.37);

        if ($device->brand && $device->model) {
            $mobile = Mobile::findByBrandAndModel($device->brand, $device->model);
            if ($mobile) {
                $estimatedPrice = $mobile->estimated_price;
                $taxAmount = $estimatedPrice * 0.37;
            }
        }

        // Update device with passenger info (not paid yet)
        $device->update([
            'passenger_id' => $passenger->id,
            'tax' => $taxAmount,
        ]);

        // Store data in session for payment page
        session([
            'registration_data' => [
                'device_id' => $device->id,
                'passenger_id' => $passenger->id,
                'tax_amount' => $taxAmount,
                'machine_id' => $machine->id,
            ]
        ]);

        return redirect()->route('imei.payment', ['device' => $device->id]);
    }

    public function paymentPage(MobileDevice $device) {
        $registrationData = session('registration_data');
        if (!$registrationData || $registrationData['device_id'] != $device->id) {
            return redirect()->route('imei.check')->with('error', 'يرجى إكمال التسجيل أولاً');
        }

        $passenger = Passenger::find($registrationData['passenger_id']);
        $taxAmount = $registrationData['tax_amount'];

        return view('front.imei.payment', compact('device', 'passenger', 'taxAmount'));
    }

    public function paymentSubmit(Request $request, MobileDevice $device) {
        $request->validate([
            'card_number' => 'required|string|min:16|max:19',
            'card_holder' => 'required|string|max:255',
            'expiry_date' => 'required|string',
            'cvv' => 'required|string|min:3|max:4',
        ]);

        $registrationData = session('registration_data');
        if (!$registrationData || $registrationData['device_id'] != $device->id) {
            return redirect()->route('imei.check')->with('error', 'يرجى إكمال التسجيل أولاً');
        }

        $passenger = Passenger::find($registrationData['passenger_id']);
        $taxAmount = $registrationData['tax_amount'];
        $machineId = $registrationData['machine_id'];

        // Update device as paid
        $device->update([
            'is_paid' => true,
            'is_activated' => true,
            'activated_at' => now(),
        ]);

        // Create payment record
        $payment = Payment::create([
            'machine_id' => $machineId,
            'mobile_device_id' => $device->id,
            'passenger_id' => $passenger->id,
            'amount' => $taxAmount,
            'payment_method' => 'card',
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Clear session
        session()->forget('registration_data');

        return redirect()->route('imei.payment.success', ['device' => $device->id]);
    }

    public function paymentSuccess(MobileDevice $device) {
        $passenger = $device->passenger;
        $taxAmount = $device->tax;
        $payment = Payment::where('mobile_device_id', $device->id)->latest()->first();

        return view('front.imei.payment-success', compact('device', 'passenger', 'taxAmount', 'payment'));
    }

    public function imeiCheckSubmit(Request $request, ImeiApiService $imeiApi) {
        $request->validate([
            'imei' => 'required|string|min:15|max:15|regex:/^[0-9]+$/'
        ], [
            'imei.required' => 'يرجى إدخال رقم IMEI',
            'imei.min' => 'رقم IMEI يجب أن يكون 15 رقم',
            'imei.max' => 'رقم IMEI يجب أن يكون 15 رقم',
            'imei.regex' => 'رقم IMEI يجب أن يحتوي على أرقام فقط'
        ]);

        // Get machine by token
        $result = Machine::findOrCreateByToken(
            $request->cookie('machine_token'),
            $request->ip(),
            $request->userAgent()
        );
        $machine = $result['machine'];

        $imei = $request->imei;

        // Step 1: Call external IMEI API
        $apiResponse = $imeiApi->checkImei($imei);

        // Check if device already registered in our system
        $mobileDevice = MobileDevice::byImei($imei)->first();

        // If device exists in our system, show its status
        if ($mobileDevice) {
            $estimatedPrice = 25000;
            $taxAmount = $mobileDevice->tax ?? ($estimatedPrice * 0.37);

            if ($mobileDevice->brand && $mobileDevice->model) {
                $mobile = Mobile::findByBrandAndModel($mobileDevice->brand, $mobileDevice->model);
                if ($mobile) {
                    $estimatedPrice = $mobile->estimated_price;
                    $taxAmount = $estimatedPrice * 0.37;
                }
            }

            // Log the check
            ImeiCheck::create([
                'machine_id' => $machine->id,
                'passenger_id' => $mobileDevice->passenger_id,
                'mobile_device_id' => $mobileDevice->id,
                'scanned_imei' => $imei,
                'api_response' => json_encode($apiResponse['data'] ?? null),
                'status' => 'found',
                'checked_at' => now(),
            ]);

            return view('front.imei.result', [
                'apiResponse' => $apiResponse,
                'imei' => $imei,
                'mobileDevice' => $mobileDevice,
                'taxAmount' => $taxAmount,
                'estimatedPrice' => $estimatedPrice,
                'isNewDevice' => false,
            ]);
        }

        // Device not in our system - check API response
        if (!$apiResponse['success'] || !isset($apiResponse['data']['result'])) {
            // API failed or no result - show error
            ImeiCheck::create([
                'machine_id' => $machine->id,
                'scanned_imei' => $imei,
                'api_response' => json_encode($apiResponse['data'] ?? null),
                'status' => 'not_found',
                'checked_at' => now(),
            ]);

            return view('front.imei.result', [
                'apiResponse' => [
                    'success' => false,
                    'error' => $apiResponse['error'] ?? 'لم يتم العثور على بيانات الجهاز. يرجى التأكد من رقم IMEI والمحاولة مرة أخرى.',
                ],
                'imei' => $imei,
                'mobileDevice' => null,
                'taxAmount' => 0,
                'estimatedPrice' => 0,
                'isNewDevice' => false,
            ]);
        }

        // API returned device info - create new record
        $result = $apiResponse['data']['result'];
        $brand = $result['brand_name'] ?? $result['brand'] ?? null;
        $model = $result['model'] ?? null;

        if (!$brand || !$model) {
            // API returned incomplete data
            ImeiCheck::create([
                'machine_id' => $machine->id,
                'scanned_imei' => $imei,
                'api_response' => json_encode($apiResponse['data'] ?? null),
                'status' => 'not_found',
                'checked_at' => now(),
            ]);

            return view('front.imei.result', [
                'apiResponse' => [
                    'success' => false,
                    'error' => 'لم يتم العثور على معلومات كاملة للجهاز.',
                ],
                'imei' => $imei,
                'mobileDevice' => null,
                'taxAmount' => 0,
                'estimatedPrice' => 0,
                'isNewDevice' => false,
            ]);
        }

        // Get price from mobiles table
        $estimatedPrice = 25000;
        $mobile = Mobile::findByBrandAndModel($brand, $model);
        if ($mobile) {
            $estimatedPrice = $mobile->estimated_price;
        } else {
            Mobile::create([
                'brand' => $brand,
                'model' => $model,
                'estimated_price' => 25000
            ]);
        }
        $taxAmount = $estimatedPrice * 0.37;

        // Create new MobileDevice
        $mobileDevice = MobileDevice::create([
            'device_type' => 'mobile',
            'brand' => $brand,
            'model' => $model,
            'imei_number' => $imei,
            'tax' => $taxAmount,
            'registered_at' => now(),
            'is_activated' => false,
            'is_locked' => false,
            'is_paid' => false,
        ]);

        // Log the check
        ImeiCheck::create([
            'machine_id' => $machine->id,
            'mobile_device_id' => $mobileDevice->id,
            'scanned_imei' => $imei,
            'api_response' => json_encode($apiResponse['data'] ?? null),
            'status' => 'found',
            'checked_at' => now(),
        ]);

        return view('front.imei.result', [
            'apiResponse' => $apiResponse,
            'imei' => $imei,
            'mobileDevice' => $mobileDevice,
            'taxAmount' => $taxAmount,
            'estimatedPrice' => $estimatedPrice,
            'isNewDevice' => true,
        ]);
    }

    public function index(Request $request) {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // Machine Statistics
        $totalMachines = Machine::count();
        $activeMachines = Machine::where('status', 'active')->count();
        $machinesInMaintenance = Machine::where('status', 'maintenance')->count();

        // IMEI Check Statistics
        $totalImeiChecks = ImeiCheck::count();
        $todayImeiChecks = ImeiCheck::whereDate('created_at', $today)->count();
        $weekImeiChecks = ImeiCheck::where('created_at', '>=', $thisWeek)->count();
        $completedImeiChecks = ImeiCheck::where('status', 'completed')->count();
        $pendingImeiChecks = ImeiCheck::where('status', 'pending')->count();

        // Mobile Device Statistics
        $totalDevices = MobileDevice::count();
        $paidDevices = MobileDevice::where('is_paid', true)->count();
        $unpaidDevices = MobileDevice::where('is_paid', false)->count();
        $activatedDevices = MobileDevice::where('is_activated', true)->count();
        $lockedDevices = MobileDevice::where('is_locked', true)->count();

        // Passenger Statistics
        $totalPassengers = Passenger::count();
        $todayPassengers = Passenger::whereDate('created_at', $today)->count();
        $weekPassengers = Passenger::where('created_at', '>=', $thisWeek)->count();

        // Payment Statistics
        $totalPayments = Payment::where('status', 'completed')->count();
        $todayPayments = Payment::where('status', 'completed')->whereDate('paid_at', $today)->count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $todayRevenue = Payment::where('status', 'completed')->whereDate('paid_at', $today)->sum('amount');
        $weekRevenue = Payment::where('status', 'completed')->where('paid_at', '>=', $thisWeek)->sum('amount');
        $monthRevenue = Payment::where('status', 'completed')->where('paid_at', '>=', $thisMonth)->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->count();

        // Complaints & Suggestions
        $totalComplaints = Complaint::count();
        $openComplaints = Complaint::whereIn('status', ['new', 'in_progress'])->count();
        $urgentComplaints = Complaint::where('priority', 'urgent')->whereIn('status', ['new', 'in_progress'])->count();
        $totalSuggestions = Suggestion::count();
        $newSuggestions = Suggestion::where('status', 'new')->count();

        // Recent Activity (last 10)
        $recentImeiChecks = ImeiCheck::with(['machine', 'mobileDevice', 'passenger'])
            ->latest()
            ->take(10)
            ->get();

        $recentPayments = Payment::with(['machine', 'passenger'])
            ->where('status', 'completed')
            ->latest('paid_at')
            ->take(10)
            ->get();

        // Daily stats for chart (last 7 days)
        $dailyStats = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            return [
                'date' => $date->format('m/d'),
                'label' => $date->format('D'),
                'checks' => ImeiCheck::whereDate('created_at', $date)->count(),
                'payments' => Payment::where('status', 'completed')->whereDate('paid_at', $date)->count(),
                'revenue' => Payment::where('status', 'completed')->whereDate('paid_at', $date)->sum('amount'),
            ];
        });

        // Top machines by activity
        $topMachines = Machine::withCount(['imeiChecks', 'payments'])
            ->orderByDesc('imei_checks_count')
            ->take(5)
            ->get();

        // Device types distribution
        $deviceTypes = MobileDevice::select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // Nationality distribution
        $nationalities = Passenger::select('nationality', DB::raw('count(*) as count'))
            ->whereNotNull('nationality')
            ->groupBy('nationality')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalMachines',
            'activeMachines',
            'machinesInMaintenance',
            'totalImeiChecks',
            'todayImeiChecks',
            'weekImeiChecks',
            'completedImeiChecks',
            'pendingImeiChecks',
            'totalDevices',
            'paidDevices',
            'unpaidDevices',
            'activatedDevices',
            'lockedDevices',
            'totalPassengers',
            'todayPassengers',
            'weekPassengers',
            'totalPayments',
            'todayPayments',
            'totalRevenue',
            'todayRevenue',
            'weekRevenue',
            'monthRevenue',
            'pendingPayments',
            'totalComplaints',
            'openComplaints',
            'urgentComplaints',
            'totalSuggestions',
            'newSuggestions',
            'recentImeiChecks',
            'recentPayments',
            'dailyStats',
            'topMachines',
            'deviceTypes',
            'nationalities'
        ));
    }
}

