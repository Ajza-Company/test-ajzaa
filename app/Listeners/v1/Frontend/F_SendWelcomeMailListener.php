<?php

namespace App\Listeners\v1\Frontend;

use App\Events\v1\Frontend\F_UserCreatedEvent;
use App\Mail\v1\Frontend\F_WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class F_SendWelcomeMailListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(F_UserCreatedEvent $event): void
    {
        Mail::to($event->user->email)->send(new F_WelcomeMail($event->user));
    }
}
