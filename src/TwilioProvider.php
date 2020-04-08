<?php

namespace NotificationChannels\Twilio;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\Twilio\Exceptions\InvalidConfigException;
use Twilio\Rest\Client as TwilioService;

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

        $this->app->bind(TwilioService::class, function (Application $app) {
            /** @var TwilioConfig $config */
            $config = $app->make(TwilioConfig::class);

            if ($config->usingUsernamePasswordAuth()) {
                return new TwilioService($config->getUsername(), $config->getPassword(), $config->getAccountSid());
            }

            if ($config->usingTokenAuth()) {
                return new TwilioService($config->getAccountSid(), $config->getAuthToken());
            }

            throw InvalidConfigException::missingConfig();
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/twilio-notification-channel.php', '');

        $this->publishes([
            __DIR__.'/../config/twilio-notification-channel.php' => config_path('twilio-notification-channel.php'),
        ]);

        $this->app->bind(TwilioConfig::class, function () {
            return new TwilioConfig($this->app['config']['twilio-notification-channel']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            TwilioConfig::class,
            TwilioService::class,
            TwilioChannel::class,
        ];
    }
}
