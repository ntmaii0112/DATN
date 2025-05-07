<?php

namespace App\Providers;
use App\Services\EmailService;
use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('email-service', function() {
            return new EmailService();
        });
    }
}
