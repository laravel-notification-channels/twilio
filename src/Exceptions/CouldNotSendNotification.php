<?php

declare(strict_types=1);

namespace NotificationChannels\Twilio\Exceptions;

use NotificationChannels\Twilio\TwilioCallMessage;
use NotificationChannels\Twilio\TwilioSmsMessage;

class CouldNotSendNotification extends \Exception
{
    public static function invalidMessageObject($message): self
    {
        $className = is_object($message) ? get_class($message) : 'Unknown';

        return new static(
            "Notification was not sent. Message object class `{$className}` is invalid. It should
            be either `".TwilioSmsMessage::class.'` or `'.TwilioCallMessage::class.'`');
    }

    public static function missingFrom(): self
    {
        return new static('Notification was not sent. Missing `from` number.');
    }

    public static function invalidReceiver(): self
    {
        return new static(
            'The notifiable did not have a receiving phone number. Add a routeNotificationForTwilio
            method or a phone_number attribute to your notifiable.'
        );
    }

    public static function missingAlphaNumericSender(): self
    {
        return new static(
            'Notification was not sent. Missing `alphanumeric_sender` in config'
        );
    }
}
