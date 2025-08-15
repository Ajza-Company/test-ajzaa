<?php

namespace App\Listeners\v1\Frontend;

use App\Events\v1\Frontend\F_UserCreatedEvent;
use App\Repositories\General\FcmToken\Create\G_CreateFcmTokenInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class F_CreateUserFcmTokenListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(private G_CreateFcmTokenInterface $createFcmToken)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(F_UserCreatedEvent $event): void
    {
        if ($event->token) {
            $this->createFcmToken->create([
                'user_id' => $event->user->id,
                'token' => $event->token
            ]);
        }
    }
}
