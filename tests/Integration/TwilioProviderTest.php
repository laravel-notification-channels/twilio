<?php

declare(strict_types=1);

namespace NotificationChannels\Twilio\Tests\Integration;

use NotificationChannels\Twilio\Exceptions\InvalidConfigException;
use NotificationChannels\Twilio\TwilioChannel;

class TwilioProviderTest extends BaseIntegrationTest
{
    public function testThatApplicationCannotCreateChannelWithoutConfig()
    {
        $this->expectException(InvalidConfigException::class);

        $this->app->get(TwilioChannel::class);
    }

    public function testThatApplicationCannotCreateChannelWithoutSid()
    {
        $this->app['config']->set('twilio-notification-channel.username', 'test');
        $this->app['config']->set('twilio-notification-channel.username', 'password');

        $this->expectException(InvalidConfigException::class);
        $this->app->get(TwilioChannel::class);
    }

    public function testThatApplicationCreatesChannelWithConfig()
    {
        $this->app['config']->set('twilio-notification-channel.username', 'test');
        $this->app['config']->set('twilio-notification-channel.password', 'password');
        $this->app['config']->set('twilio-notification-channel.account_sid', '1234');

        $this->assertInstanceOf(TwilioChannel::class, $this->app->get(TwilioChannel::class));
    }
}
