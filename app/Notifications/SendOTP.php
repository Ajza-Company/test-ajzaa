<?php

namespace App\Notifications;

use App\Broadcasting\Sms\SMSChannel;
use App\Broadcasting\Sms\SmsContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOTP extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [SMSChannel::class];
    }

    /**
     * Send Notification Using SMS
     *
     * @param  mixed  $notifiable
     * @return SmsContent
     */
    public function toSms($notifiable): SmsContent
    {
        return (new SmsContent)->to($notifiable)->message($this->message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
