<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IPROGSMSService
{
    /**
     * Send SMS to one or more phone numbers using IPROG SMS API.
     *
     * @param array|string $phoneNumbers
     * @param string $message
     * @return array
     */
    public function sendSMS($phoneNumbers, $message)
    {
        $apiToken = env('IPROG_SMS_API_KEY');
        $url = env('IPROG_SMS_API_URL');

        // Ensure $phoneNumbers is always an array
        $phoneNumbers = is_array($phoneNumbers) ? $phoneNumbers : [$phoneNumbers];

        foreach ($phoneNumbers as $number) {
            // Convert 09XXXXXXXXX to 63XXXXXXXXX
            if (str_starts_with($number, '09')) {
                $number = '63' . substr($number, 1);
            }

            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post($url, [
                    'api_token'     => $apiToken,
                    'phone_number'  => $number,
                    'message'       => $message,
                    'sms_provider'  => 1,
                ]);

                Log::info('IPROG SMS API response', [
                    'number' => $number,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                if ($response->status() !== 200) {
                    return [
                        'success'  => false,
                        'message'  => 'Failed to send SMS to ' . $number,
                        'response' => $response->json(),
                    ];
                }

            } catch (\Exception $e) {
                Log::error('IPROG SMS API exception', [
                    'number' => $number,
                    'error'  => $e->getMessage(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Exception occurred while sending SMS to ' . $number,
                    'error'   => $e->getMessage(),
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'All SMS sent successfully!',
        ];
    }
}
