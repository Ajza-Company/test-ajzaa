<?php

namespace App\Broadcasting\FCM;

use Google\Client;
use Google\Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FCMChannel
{
    private $client;

    /**
     * Create a new channel instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct()
    {
        // Channel initialization, if necessary
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/ajza-4ad8b-firebase-adminsdk-ufolh-1608b44ec9.json'));
        $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    }

    private function getAccessToken()
    {
        $token = $this->client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }

    /**
     * Send the given notification to multiple tokens.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $accessToken = $this->getAccessToken();
        $data = $notification->toFCM($notifiable);

        if (empty($data->tokens)) {
            Log::warning('No tokens provided for FCM notification');
            return;
        }

        $url = 'https://fcm.googleapis.com/v1/projects/ajza-4ad8b/messages:send';
        $tokens = is_array($data->tokens) ? $data->tokens : [$data->tokens];

        foreach ($tokens as $token) {
            $requestData = [
                'message' => [
                    "token" => $token,
                    "notification" => [
                        "title" => $data->title,
                        "body" => $data->body,
                    ],
                    'data' => $data->data,
                    "apns" => [
                        'headers' => [
                            'apns-priority' => '10',
                        ],
                        'payload' => [
                            "aps" => [
                                'alert' => [
                                    'title' => $data->title,
                                    'body' => $data->body,
                                ],
                                /*"critical-sound" => [
                                    "critical" => false,
                                    "name" => "default",
                                    "volume" => 1.0
                                ],
                                "interruption-level" => "critical",*/
                            ],
                        ]
                    ],
                ]
            ];

            try {
                $response = Http::withHeaders([
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer " . $accessToken,
                ])->post($url, $requestData);

                if ($response->ok()) {
                    Log::info('FCM Notification sent successfully', [
                        'response' => $response->json(),
                    ]);
                } else {
                    Log::error('FCM Notification failed to send to token: ' . $token, [
                        'response' => $response->json(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('An error occurred while sending FCM Notification to token: ' . $token, [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
