<?php

namespace NotificationChannels\Twilio;

use Illuminate\Contracts\Events\Dispatcher;
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
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/twilio-notification-channel.php', 'twilio-notification-channel');

        $this->publishes([
            __DIR__.'/../config/twilio-notification-channel.php' => config_path('twilio-notification-channel.php'),
        ]);

        $this->app->bind(TwilioConfig::class, function () {
            return new TwilioConfig($this->app['config']['twilio-notification-channel']);
        });

        $this->app->singleton(TwilioService::class, function (Application $app) {
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

        $this->app->singleton(Twilio::class, function (Application $app) {
            return new Twilio(
                $app->make(TwilioService::class),
                $app->make(TwilioConfig::class)
            );
        });

        $this->app->singleton(TwilioChannel::class, function (Application $app) {
            return new TwilioChannel(
                $app->make(Twilio::class),
                $app->make(Dispatcher::class)
            );
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
