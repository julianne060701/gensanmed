<?php
namespace App\Services;

use ClickSend\Api\SMSApi;
use ClickSend\Configuration;
use GuzzleHttp\Client;

use ClickSend\Model\SmsMessage;
use ClickSend\Model\SmsMessageCollection;

class ClickSendSMSService
{
    protected $smsApi;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()
            ->setUsername(config('clicksend.username'))
            ->setPassword(config('clicksend.api_key'));
    
        $this->smsApi = new SMSApi(new Client(), $config);
    }
    

    public function sendSMS($recipients, $message)
    {
        $messages = [];

        foreach ($recipients as $phone) {
            $messages[] = [
                'to' => $phone,
                'body' => $message
            ];
        }

        $smsCollection = new SmsMessageCollection(['messages' => $messages]);

        try {
            $response = $this->smsApi->smsSendPost($smsCollection);
            return ['success' => true, 'message' => 'SMS sent successfully!', 'response' => $response];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()];
        }
    }
}