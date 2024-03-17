<?php

declare(strict_types=1);

namespace NotificationChannels\Twilio\Exceptions;

class InvalidConfigException extends \Exception
{
    public static function missingConfig(): self
    {
        return new self('Missing config. You must set either the username & password or SID and auth token');
    }

    public static function multipleContentTypes(): self
    {
        return new self('Unable to use URL and TWIML call types simultaneously. You can use only one type');
    }
}
