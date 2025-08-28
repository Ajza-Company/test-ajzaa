<?php

namespace App\Repositories\SMS\Providers;

use App\Repositories\SMS\OTPProviderInterface;
use App\Repositories\SMS\SMSProviderInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Provider1 implements SMSProviderInterface, OTPProviderInterface
{
    /**
     * @throws ConnectionException
     */
    public function sendMessage(string $to, string $message): bool
    {
        // Using jawalbsms API exactly as provided by SMS support
        $username = config('services.sms.provider1.username');
        $password = config('services.sms.provider1.password'); 
        $sender = config('services.sms.provider1.sender');
        
        // Build the jawalbsms API URL with query parameters
        $url = "https://www.jawalbsms.ws/api.php/sendsms";
        
        try {
            // Use cURL exactly as jawalbsms documentation shows
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
            curl_setopt($ch, CURLOPT_HEADER, 0);
            
            // Add query parameters
            $queryParams = http_build_query([
                'user' => $username,
                'pass' => $password,
                'to' => $to,
                'message' => $message,
                'sender' => $sender
            ]);
            
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $queryParams);
            
            $data = curl_exec($ch);
            curl_close($ch);
            
            Log::info('JawalbSMS Response', [
                'to' => $to,
                'response' => $data,
                'url' => $url . '?' . $queryParams
            ]);
            
            // According to jawalbsms docs:
            // - Positive numbers indicate success (message ID)
            // - Negative numbers indicate errors (-100, -115, etc.)
            $result = trim($data);
            
            if (is_numeric($result) && $result > 0) {
                return true;
            }
            
            // Log error codes for debugging
            if (is_numeric($result)) {
                $errorMsg = match((int)$result) {
                    -100 => 'Username or password wrong - Check your jawalbsms credentials',
                    -115 => 'Sender name empty or wrong/not activated - Visit http://www.jawalbsms.ws/sms.php/sender to check',
                    -401 => 'Unauthorized access - Account may be suspended or API access denied. Contact jawalbsms support',
                    default => 'Unknown error code: ' . $result . ' - Contact jawalbsms support'
                };
                Log::error('JawalbSMS Error', ['code' => $result, 'message' => $errorMsg]);
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::error('JawalbSMS Exception', [
                'message' => $e->getMessage(),
                'to' => $to
            ]);
            return false;
        }
    }

    /**
     * @throws ConnectionException
     */
    public function generateAndSendOTP(string $phone): array
    {
        // Provider1 handles OTP generation internally
        $response = $this->sendMessage($phone, '');

        return [
            'success' => $response,
            'expiresAt' => now()->addMinutes(10)
        ];
    }

    /**
     * @throws ConnectionException
     */
    public function verifyOTP(string $phone, string $code): bool
    {
        // Implement Provider1's OTP verification API call
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Authorization' => config('services.sms.provider1.secret'),
        ])->post(config('services.sms.provider1.url') . '/verifyOTP', [
            'phone' => $phone,
            'otp' => $code
        ]);

        \Log::info($response->json());

        return $response->ok();
    }
}
