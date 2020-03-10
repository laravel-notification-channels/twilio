<?php

namespace NotificationChannels\Twilio;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client as TwilioService;
use Illuminate\Contracts\Support\DeferrableProvider;

class TwilioProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(TwilioChannel::class)
            ->needs(Twilio::class)
            ->give(function () {
                return new Twilio(
                    $this->app->make(TwilioService::class),
                    $this->app->make(TwilioConfig::class)
                );
            });

        $this->app->bind(TwilioService::class, function () {
            $config = $this->app['config']['services.twilio'];
            $account_sid = Arr::get($config, 'account_sid');
            $username = Arr::get($config, 'username');
            if (! empty($username)) {
                $password = Arr::get($config, 'password');

                return new TwilioService($username, $password, $account_sid);
            }

            $auth_token = Arr::get($config, 'auth_token');

            return new TwilioService($account_sid, $auth_token);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(TwilioConfig::class, function () {
            return new TwilioConfig($this->app['config']['services.twilio']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            TwilioConfig::class,
            TwilioService::class,
            TwilioChannel::class,
        ];
    }
}
