<?php
// namespace App\Services;

// use GuzzleHttp\Client;

// class PhilSMSService
// {
//     protected $client;
//     protected $apiUrl;
//     protected $apiToken;

//     public function __construct()
//     {
//         $this->client = new Client();
//         $this->apiUrl = config('philsms.api_url');
//         $this->apiToken = config('philsms.api_token');
//     }

//     public function sendSMS($recipients, $message)
//     {
//         $messages = [];
//         foreach ($recipients as $phone) {
//             $messages[] = [
//                 'recipient' => $phone,
//                 'message' => $message,
//             ];
//         }

//         try {
//             $response = $this->client->post($this->apiUrl . 'send-bulk-sms', [
//                 'headers' => [
//                     'Authorization' => 'Bearer ' . $this->apiToken,
//                     'Content-Type' => 'application/json',
//                 ],
//                 'json' => [
//                     'messages' => $messages,
//                 ],
//             ]);

//             return [
//                 'success' => true,
//                 'message' => 'SMS sent successfully!',
//                 'response' => json_decode($response->getBody(), true),
//             ];
//         } catch (\Exception $e) {
//             return [
//                 'success' => false,
//                 'message' => 'Failed to send SMS: ' . $e->getMessage(),
//             ];
//         }
//     }
// }
