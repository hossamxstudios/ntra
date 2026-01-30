<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImeiApiService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected int $serviceId;

    public function __construct()
    {
        $this->baseUrl = config('services.imei.url');
        $this->apiKey = config('services.imei.key');
        $this->serviceId = config('services.imei.service_id');
    }

    public function checkImei(string $imei): array
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/check/{$this->serviceId}/", [
                'API_KEY' => $this->apiKey,
                'imei' => $imei,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'status_code' => $response->status(),
                    'data' => $data,
                    'is_pending' => $response->status() === 202,
                ];
            }

            return [
                'success' => false,
                'status_code' => $response->status(),
                'error' => $this->getErrorMessage($response->status()),
                'data' => $response->json(),
            ];

        } catch (\Exception $e) {
            Log::error('IMEI API Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'status_code' => 500,
                'error' => 'حدث خطأ أثناء الاتصال بالخدمة. يرجى المحاولة مرة أخرى.',
                'exception' => $e->getMessage(),
            ];
        }
    }

    protected function getErrorMessage(int $statusCode): string
    {
        return match ($statusCode) {
            401 => 'مفتاح API غير صالح',
            403 => 'لم يتم توفير بيانات المصادقة',
            404 => 'الخدمة غير موجودة',
            default => 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.',
        };
    }
}
