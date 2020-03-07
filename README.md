# Twilio notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/twilio.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/twilio)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/twilio/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/twilio)
[![StyleCI](https://styleci.io/repos/65543339/shield)](https://styleci.io/repos/65543339)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/twilio.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/twilio)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/twilio/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/twilio/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/twilio.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/twilio)

This package makes it easy to send [Twilio notifications](https://documentation.twilio.com/docs) with Laravel 5.5+, 6.x and 7.x

## Contents

- [Installation](#installation)
	- [Setting up your Twilio account](#setting-up-your-twilio-account)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/twilio
```

### Setting up your Twilio account

Add your Twilio Account SID, Auth Token, and From Number (optional) to your `config/services.php`:

```php
// config/services.php
...
'twilio' => [
    'username' => env('TWILIO_USERNAME'), // optional when using auth token
    'password' => env('TWILIO_PASSWORD'), // optional when using auth token
    'auth_token' => env('TWILIO_AUTH_TOKEN'), // optional when using username and password
    'account_sid' => env('TWILIO_ACCOUNT_SID'),
    'from' => env('TWILIO_FROM'), // optional
],
...
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

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

You can also send an MMS:

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

Or create a Twilio call:

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

If you discover any security related issues, please email gregoriohc@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Gregorio Hernández Caso](https://github.com/gregoriohc)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
