<?php

namespace NotificationChannels\Twilio\Test;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Twilio\TwilioCallMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use PHPUnit_Framework_TestCase;
use Services_Twilio as Twilio;
use Services_Twilio_Rest_Calls;
use Services_Twilio_Rest_Messages;

class IntegrationTest extends PHPUnit_Framework_TestCase
{
    /** @var Twilio */
    protected $twilio;

    /** @var Notification */
    protected $notification;

    /** @var Dispatcher */
    protected $events;

    public function setUp()
    {
        parent::setUp();

        $this->twilio = Mockery::mock(Twilio::class);
        $this->twilio->account = new \stdClass();
        $this->twilio->account->messages = Mockery::mock(Services_Twilio_Rest_Messages::class);
        $this->twilio->account->calls = Mockery::mock(Services_Twilio_Rest_Calls::class);

        $this->events = Mockery::mock(Dispatcher::class);
        $this->notification = Mockery::mock(Notification::class);
    }

    /** @test */
    public function it_can_send_a_sms_message()
    {
        $message = TwilioSmsMessage::create('Message text');
        $this->notification->shouldReceive('toTwilio')->andReturn($message);

        $channel = new TwilioChannel($this->twilio, $this->events, '+31612345678');

        $this->smsMessageWillBeSentToTwilioWith('+31612345678', '+22222222222', 'Message text');

        $channel->send(new NotifiableWithAttribute(), $this->notification);
    }

    /** @test */
    public function it_can_make_a_call()
    {
        $message = TwilioCallMessage::create('http://example.com');
        $this->notification->shouldReceive('toTwilio')->andReturn($message);

        $channel = new TwilioChannel($this->twilio, $this->events, '+31612345678');

        $this->callWillBeSentToTwilioWith('+31612345678', '+22222222222', 'http://example.com');

        $channel->send(new NotifiableWithAttribute(), $this->notification);
    }

    protected function smsMessageWillBeSentToTwilioWith($from, $to, $message)
    {
        $this->twilio->account->messages->shouldReceive('sendMessage')
            ->atLeast()->once()
            ->with($from, $to, $message)
            ->andReturn(true);
    }

    protected function callWillBeSentToTwilioWith($from, $to, $url)
    {
        $this->twilio->account->calls->shouldReceive('create')
            ->atLeast()->once()
            ->with($from, $to, $url)
            ->andReturn(true);
    }
}
