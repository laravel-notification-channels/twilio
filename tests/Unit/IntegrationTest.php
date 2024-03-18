<?php

namespace NotificationChannels\Twilio\Tests\Unit;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Notification;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\Twilio\Twilio;
use NotificationChannels\Twilio\TwilioCallMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioConfig;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Twilio\Rest\Api\V2010\Account\CallInstance;
use Twilio\Rest\Api\V2010\Account\CallList;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Api\V2010\Account\MessageList;
use Twilio\Rest\Client as TwilioService;

class IntegrationTest extends MockeryTestCase
{
    /** @var TwilioService */
    protected $twilioService;

    /** @var Notification */
    protected $notification;

    /** @var Dispatcher */
    protected $events;

    public function setUp(): void
    {
        parent::setUp();

        $this->twilioService = Mockery::mock(TwilioService::class);
        $this->twilioService->messages = Mockery::mock(MessageList::class);
        $this->twilioService->calls = Mockery::mock(CallList::class);

        $this->events = Mockery::mock(Dispatcher::class);
        $this->notification = Mockery::mock(Notification::class);
    }

    /** @test */
    public function it_can_send_a_sms_message()
    {
        $message = TwilioSmsMessage::create('Message text');
        $this->notification->shouldReceive('toTwilio')->andReturn($message);

        $config = new TwilioConfig([
            'from' => '+31612345678',
        ]);
        $twilio = new Twilio($this->twilioService, $config);
        $channel = new TwilioChannel($twilio, $this->events);

        $this->smsMessageWillBeSentToTwilioWith('+22222222222', [
            'from' => '+31612345678',
            'body' => 'Message text',
        ]);

        $channel->send(new NotifiableWithAttribute(), $this->notification);
    }

    /** @test */
    public function it_can_send_a_sms_message_using_service()
    {
        $message = TwilioSmsMessage::create('Message text');
        $this->notification->shouldReceive('toTwilio')->andReturn($message);

        $config = new TwilioConfig([
            'from' => '+31612345678',
            'sms_service_sid' => '0123456789',
        ]);
        $twilio = new Twilio($this->twilioService, $config);
        $channel = new TwilioChannel($twilio, $this->events);

        $this->smsMessageWillBeSentToTwilioWith('+22222222222', [
            'from' => '+31612345678',
            'body' => 'Message text',
            'messagingServiceSid' => '0123456789',
        ]);

        $channel->send(new NotifiableWithAttribute(), $this->notification);
    }

    /** @test */
    public function it_can_send_a_sms_message_using_url_shortener()
    {
        $message = TwilioSmsMessage::create('Message text');
        $this->notification->shouldReceive('toTwilio')->andReturn($message);

        $config = new TwilioConfig([
            'from' => '+31612345678',
            'ShortenUrls' => true,
        ]);
        $twilio = new Twilio($this->twilioService, $config);
        $channel = new TwilioChannel($twilio, $this->events);

        $this->smsMessageWillBeSentToTwilioWith('+22222222222', [
            'from' => '+31612345678',
            'body' => 'Message text',
            'ShortenUrls' => 'true',
        ]);

        $channel->send(new NotifiableWithAttribute(), $this->notification);
    }

    /** @test */
    public function it_can_send_a_sms_message_using_alphanumeric_sender()
    {
        $message = TwilioSmsMessage::create('Message text');
        $this->notification->shouldReceive('toTwilio')->andReturn($message);

        $config = new TwilioConfig([
            'from' => '+31612345678',
            'alphanumeric_sender' => 'TwilioTest',
        ]);
        $twilio = new Twilio($this->twilioService, $config);
        $channel = new TwilioChannel($twilio, $this->events);

        $this->smsMessageWillBeSentToTwilioWith('+33333333333', [
            'from' => 'TwilioTest',
            'body' => 'Message text',
        ]);

        $channel->send(new NotifiableWithAlphanumericSender(), $this->notification);
    }

    /** @test */
    public function it_can_make_a_call()
    {
        $message = TwilioCallMessage::create('http://example.com');
        $this->notification->shouldReceive('toTwilio')->andReturn($message);

        $config = new TwilioConfig([
            'from' => '+31612345678',
        ]);
        $twilio = new Twilio($this->twilioService, $config);
        $channel = new TwilioChannel($twilio, $this->events);

        $this->callWillBeSentToTwilioWith('+22222222222', '+31612345678', [
            'url' => 'http://example.com',
        ]);

        $channel->send(new NotifiableWithAttribute(), $this->notification);
    }

    protected function smsMessageWillBeSentToTwilioWith(...$args)
    {
        $this->twilioService->messages->shouldReceive('create')
            ->atLeast()->once()
            ->with(...$args)
            ->andReturn(Mockery::mock(MessageInstance::class));
    }

    protected function callWillBeSentToTwilioWith(...$args)
    {
        $this->twilioService->calls->shouldReceive('create')
            ->atLeast()->once()
            ->with(...$args)
            ->andReturn(Mockery::mock(CallInstance::class));
    }
}
