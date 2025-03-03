<?php

namespace NotificationChannels\Twilio\Tests\Unit;

use NotificationChannels\Twilio\TwilioConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TwilioConfigTest extends TestCase
{
    private function config(array $config = []): TwilioConfig
    {
        return new TwilioConfig($config);
    }

    #[Test]
    public function it_returns_a_boolean_wether_it_is_is_enabled_or_not()
    {
        $this->assertTrue($this->config()->enabled()); // defaults to true
        $this->assertTrue($this->config(['enabled' => true])->enabled());
        $this->assertFalse($this->config(['enabled' => false])->enabled());
    }

    #[Test]
    public function it_defaults_to_null_for_config_keys_with_a_string_return_type()
    {
        $config = $this->config();

        $this->assertNull($config->getAuthToken());
        $this->assertNull($config->getUsername());
        $this->assertNull($config->getPassword());
        $this->assertNull($config->getAccountSid());
        $this->assertNull($config->getFrom());
        $this->assertNull($config->getAlphanumericSender());
        $this->assertNull($config->getServiceSid());
        $this->assertNull($config->getDebugTo());
    }

    #[Test]
    public function it_returns_a_string_for_config_keys_with_a_string_return_type()
    {
        $config = $this->config([
            'auth_token' => 'valid-auth-token',
            'username' => 'valid-username',
            'password' => 'valid-password',
            'account_sid' => 'valid-account-sid',
            'from' => 'valid-from',
            'alphanumeric_sender' => 'valid-alphanumeric-sender',
            'sms_service_sid' => 'valid-sms-service-sid',
            'debug_to' => 'valid-debug-to',
        ]);

        $this->assertEquals('valid-auth-token', $config->getAuthToken());
        $this->assertEquals('valid-username', $config->getUsername());
        $this->assertEquals('valid-password', $config->getPassword());
        $this->assertEquals('valid-account-sid', $config->getAccountSid());
        $this->assertEquals('valid-from', $config->getFrom());
        $this->assertEquals('valid-alphanumeric-sender', $config->getAlphanumericSender());
        $this->assertEquals('valid-sms-service-sid', $config->getServiceSid());
        $this->assertEquals('valid-debug-to', $config->getDebugTo());
    }

    #[Test]
    public function it_returns_an_array_of_ignored_codes()
    {
        $this->assertEquals([], $this->config()->getIgnoredErrorCodes()); // defaults to empty array
        $this->assertEquals([1, 2], $this->config(['ignored_error_codes' => [1, 2]])->getIgnoredErrorCodes());
    }

    #[Test]
    public function it_returns_a_boolean_wether_the_error_code_is_ignored_or_not()
    {
        $config = $this->config(['ignored_error_codes' => [1, 2]]);
        $this->assertTrue($config->isIgnoredErrorCode(1));
        $this->assertTrue($config->isIgnoredErrorCode(2));
        $this->assertFalse($config->isIgnoredErrorCode(3));

        $config = $this->config(['ignored_error_codes' => ['*']]);
        $this->assertTrue($config->isIgnoredErrorCode(1));
        $this->assertTrue($config->isIgnoredErrorCode(2));
        $this->assertTrue($config->isIgnoredErrorCode(3));
    }

    #[Test]
    public function it_returns_a_boolean_wether_shorten_urls_is_enabled_or_not()
    {
        $this->assertFalse($this->config()->isShortenUrlsEnabled()); // defaults to false
        $this->assertTrue($this->config(['shorten_urls' => true])->isShortenUrlsEnabled());
        $this->assertFalse($this->config(['shorten_urls' => false])->isShortenUrlsEnabled());
    }

    #[Test]
    public function it_returns_a_boolean_wether_token_auth_is_used_or_not()
    {
        // No values set...
        $this->assertFalse($this->config()->usingTokenAuth());

        // One value set...
        $this->assertFalse($this->config(['auth_token' => 'valid'])->usingTokenAuth());
        $this->assertFalse($this->config(['account_sid' => 'valid'])->usingTokenAuth());

        // Both values set...
        $this->assertTrue($this->config(['auth_token' => 'valid', 'account_sid' => 'valid'])->usingTokenAuth());
    }

    #[Test]
    public function it_returns_a_boolean_wether_username_password_auth_is_used_or_not()
    {
        // No values set...
        $this->assertFalse($this->config()->usingUsernamePasswordAuth());

        // One value set...
        $this->assertFalse($this->config(['username' => 'valid'])->usingUsernamePasswordAuth());
        $this->assertFalse($this->config(['password' => 'valid'])->usingUsernamePasswordAuth());
        $this->assertFalse($this->config(['account_sid' => 'valid'])->usingUsernamePasswordAuth());

        // Two values set...
        $this->assertFalse($this->config(['username' => 'valid', 'password' => 'valid'])->usingUsernamePasswordAuth());
        $this->assertFalse($this->config(['username' => 'valid', 'account_sid' => 'valid'])->usingUsernamePasswordAuth());
        $this->assertFalse($this->config(['password' => 'valid', 'account_sid' => 'valid'])->usingUsernamePasswordAuth());

        // All values set...
        $this->assertTrue($this->config(['username' => 'valid', 'password' => 'valid', 'account_sid' => 'valid'])->usingUsernamePasswordAuth());
    }
}
