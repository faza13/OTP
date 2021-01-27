<?php

namespace Faza13\OTP;


use Faza13\OTP\Contracts\OTPInterface;
use Illuminate\Support\ServiceProvider;

class OTPServiceProvider extends ServiceProvider
{

    public function boot(){
        $this->publishes([
            __DIR__ . '/config/otp.php' => $this->app->basePath('config/otp.php'),
        ]);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/../config/otp.php', 'otp');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->app->singleton(OTPInterface::class, function ($app) {
            switch ($app->make('config')->get('otp.default')) {
                case 'firebase':
                    return new OTPFirebase($app->make('config')->get('otp.firebase.api_key'));
                default:
                    throw new \RuntimeException("Unknown Stock Checker Service");
            }
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['otp'];
    }
}
