<?php

namespace App\Broadcasting\Sms;

class SmsContent {
    public $to;
    public $message;

    public function __construct()
    {
    }

    public function to($phone)
    {
        $this->to = $phone;
        return $this;
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function toArray()
    {
        $data = [];
        $data['to'] = $this->to;
        $data['message'] = $this->message;
        return $data;
    }
}
