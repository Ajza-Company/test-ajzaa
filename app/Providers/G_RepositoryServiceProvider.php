<?php

namespace App\Providers;

use App\Repositories\General\FcmToken\Create\G_CreateFcmTokenInterface;
use App\Repositories\General\FcmToken\Create\G_CreateFcmTokenRepository;
use Illuminate\Support\ServiceProvider;

class G_RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            G_CreateFcmTokenInterface::class,
            G_CreateFcmTokenRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
