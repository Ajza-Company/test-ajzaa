<?php

namespace App\Broadcasting\FCM;

class FCMContent
{
    public $title;
    public $body;
    public $tokens;
    public $image;

    public function __construct()
    {
    }

    public function to(array $deviceTokens)
    {
        $this->tokens = $deviceTokens;
        return $this;
    }

    public function title($title)
    {
        $this->title = $title;
        return $this;
    }
    public function body($body)
    {
        $this->body = $body;
        return $this;
    }

    public function image($image)
    {
        $this->image = $image;
        return $this;
    }

    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    public function toArray()
    {
        $data = [];
        $data['message'] = $this->message;
        return $data;
    }
}
