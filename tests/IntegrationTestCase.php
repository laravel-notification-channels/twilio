<?php

declare(strict_types=1);

namespace NotificationChannels\Twilio\Tests;

use NotificationChannels\Twilio\TwilioProvider;
use Orchestra\Testbench\TestCase;

class IntegrationTestCase extends TestCase
{
    protected static $latestResponse;

    protected function getPackageProviders($app)
    {
        return [TwilioProvider::class];
    }
}
