<?php

declare(strict_types=1);

namespace NotificationChannels\Twilio\Tests\Integration;

use NotificationChannels\Twilio\Exceptions\InvalidConfigException;
use NotificationChannels\Twilio\Tests\IntegrationTestCase;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioConfig;
use NotificationChannels\Twilio\TwilioProvider;
use PHPUnit\Framework\Attributes\Test;
use Twilio\Rest\Client;

class TwilioProviderTest extends IntegrationTestCase
{
    #[Test]
    public function it_cannot_create_the_application_without_config()
    {
        $this->expectException(InvalidConfigException::class);

        $this->app->get(TwilioChannel::class);
    }

    #[Test]
    public function it_cannot_create_the_application_without_sid()
    {
        $this->app['config']->set('twilio-notification-channel.username', 'test');
        $this->app['config']->set('twilio-notification-channel.username', 'password');

        $this->expectException(InvalidConfigException::class);
        $this->app->get(TwilioChannel::class);
    }

    #[Test]
    public function it_can_create_the_application_with_sid()
    {
        $this->app['config']->set('twilio-notification-channel.username', 'test');
        $this->app['config']->set('twilio-notification-channel.password', 'password');
        $this->app['config']->set('twilio-notification-channel.account_sid', '1234');

        $this->assertInstanceOf(TwilioChannel::class, $this->app->get(TwilioChannel::class));
    }

    #[Test]
    public function it_can_create_the_application_with_token_auth()
    {
        $this->app['config']->set('twilio-notification-channel.auth_token', 'token');
        $this->app['config']->set('twilio-notification-channel.account_sid', '1234');

        $this->assertInstanceOf(TwilioChannel::class, $this->app->get(TwilioChannel::class));
    }

    #[Test]
    public function it_provides_three_classes()
    {
        $provides = (new TwilioProvider($this->app))->provides();

        $this->assertTrue(in_array(TwilioChannel::class, $provides));
        $this->assertTrue(in_array(TwilioConfig::class, $provides));
        $this->assertTrue(in_array(Client::class, $provides));
    }
}
