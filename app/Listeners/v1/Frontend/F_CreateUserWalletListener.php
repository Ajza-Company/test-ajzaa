<?php

namespace App\Listeners\v1\Frontend;

use App\Events\v1\Frontend\F_UserCreatedEvent;
use App\Repositories\Frontend\Wallet\Create\F_CreateWalletInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class F_CreateUserWalletListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(private F_CreateWalletInterface $createWallet)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(F_UserCreatedEvent $event): void
    {
        $this->createWallet->create(['user_id' => $event->user->id], [
            'user_id' => $event->user->id
        ]);
    }
}
