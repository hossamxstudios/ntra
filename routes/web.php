<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\MachineController;
use App\Http\Controllers\Admin\MobileDeviceController;
use App\Http\Controllers\Admin\PassengerController;
use App\Http\Controllers\Admin\ImeiCheckController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SuggestionController;
use App\Http\Controllers\Admin\ComplaintController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');



Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [PageController::class, 'index'])->name('dashboard');
});

// Admin Routes
Route::middleware(['auth'])->name('admin.')->group(function () {
    // Users
    Route::get('/users'                                                 , [UserController::class,                        'index'])->name('users.index');
    Route::post('/users'                                                , [UserController::class,                        'store'])->name('users.store');
    Route::post('/users/bulk-delete'                                    , [UserController::class,                   'bulkDelete'])->name('users.bulk-delete');
    Route::post('/users/bulk-restore'                                   , [UserController::class,                  'bulkRestore'])->name('users.bulk-restore');
    Route::post('/users/bulk-force-delete'                              , [UserController::class,              'bulkForceDelete'])->name('users.bulk-force-delete');
    Route::get('/users/{id}'                                            , [UserController::class,                         'show'])->name('users.show');
    Route::post('/users/{id}'                                           , [UserController::class,                       'update'])->name('users.update');
    Route::post('/users/{id}/destroy'                                   , [UserController::class,                      'destroy'])->name('users.destroy');
    Route::post('/users/{id}/restore'                                   , [UserController::class,                      'restore'])->name('users.restore');
    Route::post('/users/{id}/force-delete'                              , [UserController::class,                  'forceDelete'])->name('users.force-delete');
    Route::post('/users/{id}/toggle-status'                             , [UserController::class,                 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{id}/assign-role'                               , [UserController::class,                   'assignRole'])->name('users.assign-role');
    Route::post('/users/{id}/change-password'                           , [UserController::class,               'changePassword'])->name('users.change-password');
    // Roles & Permissions
    Route::get('/roles'                                                 , [RoleController::class,                        'index'])->name('roles.index');
    Route::get('/roles/create'                                          , [RoleController::class,                       'create'])->name('roles.create');
    Route::post('/roles'                                                , [RoleController::class,                        'store'])->name('roles.store');
    Route::post('/roles/bulk-delete'                                    , [RoleController::class,                   'bulkDelete'])->name('roles.bulk-delete');
    Route::get('/roles/{id}'                                            , [RoleController::class,                         'show'])->name('roles.show');
    Route::get('/roles/{id}/edit'                                       , [RoleController::class,                         'edit'])->name('roles.edit');
    Route::post('/roles/{id}'                                           , [RoleController::class,                       'update'])->name('roles.update');
    Route::post('/roles/{id}/destroy'                                   , [RoleController::class,                      'destroy'])->name('roles.destroy');
    Route::post('/roles/{id}/sync-permissions'                          , [RoleController::class,              'syncPermissions'])->name('roles.sync-permissions');

    // Profile
    Route::get('/profile'                                               , [ProfileController::class,                       'index'])->name('profile.index');
    Route::post('/profile'                                              , [ProfileController::class,                      'update'])->name('profile.update');
    Route::post('/profile/password'                                     , [ProfileController::class,              'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar/remove'                                , [ProfileController::class,                'removeAvatar'])->name('profile.avatar.remove');
    // Activity Logs
    Route::middleware('can:activity-logs.view')->group(function () {
        Route::get('/activity-logs'                                     , [ActivityLogController::class,                    'index'])->name('activity-logs.index');
        Route::get('/activity-logs/user/{user}'                         , [ActivityLogController::class,             'userTimeline'])->name('activity-logs.user-timeline');
        Route::get('/activity-logs/{activityLog}'                       , [ActivityLogController::class,                     'show'])->name('activity-logs.show');
    });
    Route::middleware('can:activity-logs.delete')->group(function () {
        Route::post('/activity-logs/bulk-delete'                        , [ActivityLogController::class,               'bulkDelete'])->name('activity-logs.bulk-delete');
        Route::post('/activity-logs/clear-old'                          , [ActivityLogController::class,                 'clearOld'])->name('activity-logs.clear-old');
        Route::delete('/activity-logs/{activityLog}'                    , [ActivityLogController::class,                  'destroy'])->name('activity-logs.destroy');
    });
    // Backup
    Route::middleware('can:backup.access')->group(function () {
        Route::get('/backup'                                            , [BackupController::class,                        'index'])->name('backup.index');
    });
    Route::middleware('can:backup.create')->group(function () {
        Route::get('/backup/download'                                   , [BackupController::class,                     'download'])->name('backup.download');
    });

    // ==================== NTRA System Routes ====================

    // Machines
    Route::middleware('can:machines.view')->group(function () {
        Route::get('/machines'                                          , [MachineController::class,                       'index'])->name('machines.index');
        Route::get('/machines/{machine}'                                , [MachineController::class,                        'show'])->name('machines.show');
    });
    Route::middleware('can:machines.create')->group(function () {
        Route::get('/machines/create'                                   , [MachineController::class,                      'create'])->name('machines.create');
        Route::post('/machines'                                         , [MachineController::class,                       'store'])->name('machines.store');
    });
    Route::middleware('can:machines.edit')->group(function () {
        Route::get('/machines/{machine}/edit'                           , [MachineController::class,                        'edit'])->name('machines.edit');
        Route::put('/machines/{machine}'                                , [MachineController::class,                      'update'])->name('machines.update');
        Route::post('/machines/{machine}/heartbeat'                     , [MachineController::class,                   'heartbeat'])->name('machines.heartbeat');
    });
    Route::middleware('can:machines.delete')->group(function () {
        Route::delete('/machines/{machine}'                             , [MachineController::class,                     'destroy'])->name('machines.destroy');
        Route::post('/machines/bulk-delete'                             , [MachineController::class,                  'bulkDelete'])->name('machines.bulk-delete');
    });

    // Mobile Devices
    Route::middleware('can:mobile-devices.view')->group(function () {
        Route::get('/mobile-devices'                                    , [MobileDeviceController::class,                  'index'])->name('mobile-devices.index');
        Route::get('/mobile-devices/export'                             , [MobileDeviceController::class,                 'export'])->name('mobile-devices.export');
        Route::get('/mobile-devices/{mobileDevice}'                     , [MobileDeviceController::class,                   'show'])->name('mobile-devices.show');
    });
    Route::middleware('can:mobile-devices.create')->group(function () {
        Route::get('/mobile-devices/create'                             , [MobileDeviceController::class,                 'create'])->name('mobile-devices.create');
        Route::post('/mobile-devices'                                   , [MobileDeviceController::class,                  'store'])->name('mobile-devices.store');
    });
    Route::middleware('can:mobile-devices.edit')->group(function () {
        Route::get('/mobile-devices/{mobileDevice}/edit'                , [MobileDeviceController::class,                   'edit'])->name('mobile-devices.edit');
        Route::put('/mobile-devices/{mobileDevice}'                     , [MobileDeviceController::class,                 'update'])->name('mobile-devices.update');
        Route::post('/mobile-devices/{mobileDevice}/activate'           , [MobileDeviceController::class,               'activate'])->name('mobile-devices.activate');
        Route::post('/mobile-devices/{mobileDevice}/lock'               , [MobileDeviceController::class,                   'lock'])->name('mobile-devices.lock');
        Route::post('/mobile-devices/{mobileDevice}/unlock'             , [MobileDeviceController::class,                 'unlock'])->name('mobile-devices.unlock');
    });
    Route::middleware('can:mobile-devices.delete')->group(function () {
        Route::delete('/mobile-devices/{mobileDevice}'                  , [MobileDeviceController::class,                'destroy'])->name('mobile-devices.destroy');
        Route::post('/mobile-devices/bulk-delete'                       , [MobileDeviceController::class,             'bulkDelete'])->name('mobile-devices.bulk-delete');
    });

    // Passengers
    Route::middleware('can:passengers.view')->group(function () {
        Route::get('/passengers'                                        , [PassengerController::class,                     'index'])->name('passengers.index');
        Route::get('/passengers/export'                                 , [PassengerController::class,                    'export'])->name('passengers.export');
        Route::get('/passengers/{passenger}'                            , [PassengerController::class,                      'show'])->name('passengers.show');
    });
    Route::middleware('can:passengers.create')->group(function () {
        Route::get('/passengers/create'                                 , [PassengerController::class,                    'create'])->name('passengers.create');
        Route::post('/passengers'                                       , [PassengerController::class,                     'store'])->name('passengers.store');
    });
    Route::middleware('can:passengers.edit')->group(function () {
        Route::get('/passengers/{passenger}/edit'                       , [PassengerController::class,                      'edit'])->name('passengers.edit');
        Route::put('/passengers/{passenger}'                            , [PassengerController::class,                    'update'])->name('passengers.update');
    });
    Route::middleware('can:passengers.delete')->group(function () {
        Route::delete('/passengers/{passenger}'                         , [PassengerController::class,                   'destroy'])->name('passengers.destroy');
        Route::post('/passengers/bulk-delete'                           , [PassengerController::class,                'bulkDelete'])->name('passengers.bulk-delete');
    });

    // IMEI Checks
    Route::middleware('can:imei-checks.view')->group(function () {
        Route::get('/imei-checks'                                       , [ImeiCheckController::class,                     'index'])->name('imei-checks.index');
        Route::get('/imei-checks/export'                                , [ImeiCheckController::class,                    'export'])->name('imei-checks.export');
        Route::get('/imei-checks/{imeiCheck}'                           , [ImeiCheckController::class,                      'show'])->name('imei-checks.show');
    });
    Route::middleware('can:imei-checks.create')->group(function () {
        Route::get('/imei-checks/create'                                , [ImeiCheckController::class,                    'create'])->name('imei-checks.create');
        Route::post('/imei-checks'                                      , [ImeiCheckController::class,                     'store'])->name('imei-checks.store');
        Route::post('/imei-checks/scan'                                 , [ImeiCheckController::class,                      'scan'])->name('imei-checks.scan');
    });
    Route::middleware('can:imei-checks.edit')->group(function () {
        Route::put('/imei-checks/{imeiCheck}'                           , [ImeiCheckController::class,                    'update'])->name('imei-checks.update');
        Route::post('/imei-checks/{imeiCheck}/complete'                 , [ImeiCheckController::class,                  'complete'])->name('imei-checks.complete');
        Route::post('/imei-checks/{imeiCheck}/cancel'                   , [ImeiCheckController::class,                    'cancel'])->name('imei-checks.cancel');
    });
    Route::middleware('can:imei-checks.delete')->group(function () {
        Route::delete('/imei-checks/{imeiCheck}'                        , [ImeiCheckController::class,                   'destroy'])->name('imei-checks.destroy');
        Route::post('/imei-checks/bulk-delete'                          , [ImeiCheckController::class,                'bulkDelete'])->name('imei-checks.bulk-delete');
    });

    // Payments
    Route::middleware('can:payments.view')->group(function () {
        Route::get('/payments'                                          , [PaymentController::class,                       'index'])->name('payments.index');
        Route::get('/payments/export'                                   , [PaymentController::class,                      'export'])->name('payments.export');
        Route::get('/payments/{payment}'                                , [PaymentController::class,                        'show'])->name('payments.show');
        Route::get('/payments/{payment}/receipt'                        , [PaymentController::class,                     'receipt'])->name('payments.receipt');
    });
    Route::middleware('can:payments.create')->group(function () {
        Route::get('/payments/create'                                   , [PaymentController::class,                      'create'])->name('payments.create');
        Route::post('/payments'                                         , [PaymentController::class,                       'store'])->name('payments.store');
    });
    Route::middleware('can:payments.edit')->group(function () {
        Route::put('/payments/{payment}'                                , [PaymentController::class,                      'update'])->name('payments.update');
        Route::post('/payments/{payment}/complete'                      , [PaymentController::class,                    'complete'])->name('payments.complete');
        Route::post('/payments/{payment}/refund'                        , [PaymentController::class,                      'refund'])->name('payments.refund');
    });
    Route::middleware('can:payments.delete')->group(function () {
        Route::delete('/payments/{payment}'                             , [PaymentController::class,                     'destroy'])->name('payments.destroy');
    });

    // Suggestions
    Route::middleware('can:suggestions.view')->group(function () {
        Route::get('/suggestions'                                       , [SuggestionController::class,                    'index'])->name('suggestions.index');
        Route::get('/suggestions/export'                                , [SuggestionController::class,                   'export'])->name('suggestions.export');
        Route::get('/suggestions/{suggestion}'                          , [SuggestionController::class,                     'show'])->name('suggestions.show');
    });
    Route::middleware('can:suggestions.create')->group(function () {
        Route::get('/suggestions/create'                                , [SuggestionController::class,                   'create'])->name('suggestions.create');
        Route::post('/suggestions'                                      , [SuggestionController::class,                    'store'])->name('suggestions.store');
    });
    Route::middleware('can:suggestions.edit')->group(function () {
        Route::put('/suggestions/{suggestion}'                          , [SuggestionController::class,                   'update'])->name('suggestions.update');
        Route::post('/suggestions/{suggestion}/review'                  , [SuggestionController::class,                   'review'])->name('suggestions.review');
        Route::post('/suggestions/{suggestion}/address'                 , [SuggestionController::class,                  'address'])->name('suggestions.address');
    });
    Route::middleware('can:suggestions.delete')->group(function () {
        Route::delete('/suggestions/{suggestion}'                       , [SuggestionController::class,                  'destroy'])->name('suggestions.destroy');
        Route::post('/suggestions/bulk-delete'                          , [SuggestionController::class,               'bulkDelete'])->name('suggestions.bulk-delete');
    });

    // Complaints
    Route::middleware('can:complaints.view')->group(function () {
        Route::get('/complaints'                                        , [ComplaintController::class,                     'index'])->name('complaints.index');
        Route::get('/complaints/export'                                 , [ComplaintController::class,                    'export'])->name('complaints.export');
        Route::get('/complaints/{complaint}'                            , [ComplaintController::class,                      'show'])->name('complaints.show');
    });
    Route::middleware('can:complaints.create')->group(function () {
        Route::get('/complaints/create'                                 , [ComplaintController::class,                    'create'])->name('complaints.create');
        Route::post('/complaints'                                       , [ComplaintController::class,                     'store'])->name('complaints.store');
    });
    Route::middleware('can:complaints.edit')->group(function () {
        Route::put('/complaints/{complaint}'                            , [ComplaintController::class,                    'update'])->name('complaints.update');
        Route::post('/complaints/{complaint}/start-progress'            , [ComplaintController::class,              'startProgress'])->name('complaints.start-progress');
        Route::post('/complaints/{complaint}/resolve'                   , [ComplaintController::class,                   'resolve'])->name('complaints.resolve');
        Route::post('/complaints/{complaint}/close'                     , [ComplaintController::class,                     'close'])->name('complaints.close');
        Route::post('/complaints/{complaint}/priority'                  , [ComplaintController::class,               'setPriority'])->name('complaints.priority');
    });
    Route::middleware('can:complaints.delete')->group(function () {
        Route::delete('/complaints/{complaint}'                         , [ComplaintController::class,                   'destroy'])->name('complaints.destroy');
        Route::post('/complaints/bulk-delete'                           , [ComplaintController::class,                'bulkDelete'])->name('complaints.bulk-delete');
    });
});

Route::get('/test-error/{code}', function ($code) {
    abort($code);
});
// require __DIR__.'/auth.php';
require __DIR__.'/front.php';
