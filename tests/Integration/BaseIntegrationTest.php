<?php

declare(strict_types=1);

namespace NotificationChannels\Twilio\Tests\Integration;

use NotificationChannels\Twilio\TwilioProvider;
use Orchestra\Testbench\TestCase;

abstract class BaseIntegrationTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [TwilioProvider::class];
    }
}
