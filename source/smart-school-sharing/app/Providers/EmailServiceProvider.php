<?php

namespace App\Providers;

use App\Mail\MailService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->app->bind('email-service', function() {
            return new class {
                public function send($to, $template, $data = [])
                {
                    try {
                        Mail::to($to)->send(new MailService($data, $template));
                        return true;
                    } catch (\Exception $e) {
                        \Log::error("Email sending failed: " . $e->getMessage());
                        return false;
                    }
                }
            };
        });
    }
}
