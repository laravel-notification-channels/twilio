# Twilio notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/twilio.svg)](https://packagist.org/packages/laravel-notification-channels/twilio)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Build Status](https://github.com/laravel-notification-channels/twilio/actions/workflows/ci.yml/badge.svg)](https://github.com/laravel-notification-channels/twilio/actions/workflows/ci.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/twilio.svg)](https://packagist.org/packages/laravel-notification-channels/twilio)

This package makes it easy to send [Twilio notifications](https://documentation.twilio.com/docs) with Laravel 11.x / 12.x

## Contents

- [Installation](#installation)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via Composer:

``` bash
$ composer require laravel-notification-channels/twilio
```

### Configuration

Add your Twilio Account SID, Auth Token, and From Number (optional) to your `.env`:

```dotenv
TWILIO_USERNAME=XYZ # optional when using auth token
TWILIO_PASSWORD=ZYX # optional when using auth token
TWILIO_AUTH_TOKEN=ABCD # optional when using username and password
TWILIO_ACCOUNT_SID=1234 # always required
TWILIO_FROM=100000000 # optional default from
TWILIO_ALPHA_SENDER=HELLO # optional
TWILIO_DEBUG_TO=23423423423 # Set a number that call calls/messages should be routed to for debugging
TWILIO_SMS_SERVICE_SID=MG0a0aaaaaa00aa00a00a000a00000a00a # Optional but recommended
TWILIO_SHORTEN_URLS=true # optional, enable URL shortener
```

### Advanced configuration

Run `php artisan vendor:publish --provider="NotificationChannels\Twilio\TwilioProvider"`
```
/config/twilio-notification-channel.php
```

#### Suppressing specific errors or all errors

Publish the config using the above command, and edit the `ignored_error_codes` array. You can get the list of
exception codes from [the documentation](https://www.twilio.com/docs/api/errors).

If you want to suppress all errors, you can set the option to `['*']`. The errors will not be logged but notification
failed events will still be emitted.

#### Recommended Configuration

Twilio recommends always using a [Messaging Service](https://www.twilio.com/docs/sms/services) because it gives you
 access to features like Advanced Opt-Out, Sticky Sender, Scaler, Geomatch, Shortcode Reroute, and Smart Encoding.

Having issues with SMS? Check Twilio's [best practices](https://www.twilio.com/docs/sms/services/services-best-practices).

## Upgrading from 3.x to 4.x

We have dropped support for PHP < 8.2 and the minimum Laravel version is now 11. Other than that, there are no breaking changes.

## Upgrading from 2.x to 3.x

If you're upgrading from version `2.x`, you'll need to make sure that your set environment variables match those above
in the config section. None of the environment variable names have changed, but if you used different keys in your
`services.php` config then you'll need to update them to match the above, or publish the config file and change the
`env` key.

You should also remove the old entry for `twilio` from your `services.php` config, since it's no longer used.

The main breaking change between `2.x` and `3.x` is that failed notification will now throw an exception unless they are
in the list of ignored error codes (publish the config file to edit these).

You can replicate the `2.x` behaviour by setting `'ignored_error_codes' => ['*']`, which will case all exceptions to be
suppressed.

## Usage

Now you can use the channel in your `via()` method inside the notification to send an **SMS**:

``` php
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

You can also send an **MMS**:

``` php
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioMmsMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioMmsMessage())
            ->content("Your {$notifiable->service} account was approved!")
            ->mediaUrl("https://picsum.photos/300");
    }
}
```

You can also send using **Content Templates**:

``` php
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioContentTemplateMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioContentTemplateMessage())
            ->contentSid("HXXXXXXXXXXXXXXXXXXXXXXXX")
            ->contentVariables([
                '1' => 'John Doe',
                '2' => 'ACME Inc.',
            ]);
    }
}
```

> [!NOTE]
> If sending via WhatsApp, you must add `whatsapp:` to the beginning of the phone number (i.e. `->from('whatsapp:+61428000382')`). The number must also be approved as a [WhatsApp Sender](https://www.twilio.com/console/sms/whatsapp/senders).

Or create a **Twilio Call Message**:

``` php
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioCallMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioCallMessage())
            ->url("http://example.com/your-twiml-url");
    }
}
```

In order to let your Notification know which phone are you sending/calling to, the channel will look for the `phone_number` attribute of the Notifiable model. If you want to override this behaviour, add the `routeNotificationForTwilio` method to your Notifiable model.

```php
public function routeNotificationForTwilio()
{
    return '+1234567890';
}
```

### Available Message methods

#### TwilioSmsMessage

- `from('')`: Accepts a phone to use as the notification sender.
- `content('')`: Accepts a string value for the notification body.
- `messagingServiceSid('')`: Accepts a messaging service SID to handle configuration.

#### TwilioMmsMessage

- `mediaUrl('')`: Set the message media url.

#### TwilioContentTemplateMessage

- `contentSid('')`: Set the content sid (starting with H).
- `contentVariables([...])`: Set the content variables.

#### TwilioCallMessage

- `from('')`: Accepts a phone to use as the notification sender.
- `url('')`: Accepts an url for the call TwiML.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email pipo@onlime.ch instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Philip Iezzi (Pipo)](https://github.com/onlime)
- [Pascal Baljet](https://github.com/pascalbaljet)
- [Gregorio Hernández Caso](https://github.com/gregoriohc)
- [atymic](https://github.com/atymic)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
