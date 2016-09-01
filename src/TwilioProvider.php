<?php

namespace NotificationChannels\Twilio;

use Illuminate\Support\ServiceProvider;
use Services_Twilio as Twilio;

class TwilioProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(TwilioChannel::class)
            ->needs(Twilio::class)
            ->give(function () {
                $config = $this->app['config']['services.twilio'];

                return new Twilio(
                    $config['account_sid'],
                    $config['auth_token']
                );
            });

        $this->app->when(TwilioChannel::class)
            ->needs('$from')
            ->give($this->app['config']['services.twilio.from']);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
