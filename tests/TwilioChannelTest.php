<?php

namespace NotificationChannels\Twilio\Test;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Mockery;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioMessage;
use NotificationChannels\Twilio\TwilioCallMessage;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use PHPUnit_Framework_TestCase;
use Services_Twilio as Twilio;
use Services_Twilio_Rest_Calls;
use Services_Twilio_Rest_Messages;

class TwilioChannelTest extends PHPUnit_Framework_TestCase
{
    /** @var TwilioChannel */
    protected $channel;

    /** @var Twilio */
    protected $twilio;

    /** @var Dispatcher */
    protected $dispatcher;

    public function setUp()
    {
        parent::setUp();

        $this->twilio = Mockery::mock(Twilio::class);
        $this->dispatcher = Mockery::mock(Dispatcher::class);

        $this->channel = new TwilioChannel($this->twilio, $this->dispatcher, '+1234567890');

        $this->twilio->account = new \stdClass();
        $this->twilio->account->messages = Mockery::mock(Services_Twilio_Rest_Messages::class);
        $this->twilio->account->calls = Mockery::mock(Services_Twilio_Rest_Calls::class);
    }

    /** @test */
    public function it_will_not_send_a_message_without_known_receiver()
    {
        $notifiable = new Notifiable();
        $notification = Mockery::mock(\Illuminate\Notifications\Notification::class);

        $result = $this->channel->send($notifiable, $notification);

        $this->assertNull($result);
    }

    /** @test */
    public function it_will_send_a_sms_message_to_the_result_of_the_route_method_of_the_notifiable()
    {
        $notifiable = new NotifiableWithMethod();
        $notification = Mockery::mock(Notification::class);

        $notification->shouldReceive('toTwilio')->andReturn(new TwilioSmsMessage('Message text'));

        $this->twilio->account->messages->shouldReceive('sendMessage')
            ->atLeast()->once()
            ->with('+1234567890', '+1111111111', 'Message text')
            ->andReturn(true);

        $result = $this->channel->send($notifiable, $notification);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_will_make_a_call_to_the_phone_number_attribute_of_the_notifiable()
    {
        $notifiable = new NotifiableWithAttribute();
        $notification = Mockery::mock(Notification::class);

        $notification->shouldReceive('toTwilio')->andReturn(new TwilioCallMessage('http://example.com'));

        $this->twilio->account->calls->shouldReceive('create')
            ->atLeast()->once()
            ->with('+1234567890', '+22222222222', 'http://example.com')
            ->andReturn(true);

        $result = $this->channel->send($notifiable, $notification);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_will_convert_a_string_to_a_sms_message()
    {
        $notifiable = new NotifiableWithAttribute();
        $notification = Mockery::mock(Notification::class);

        $notification->shouldReceive('toTwilio')->andReturn('Message text');

        $this->twilio->account->messages->shouldReceive('sendMessage')
            ->atLeast()->once()
            ->with(Mockery::any(), Mockery::any(), 'Message text');

        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_will_fire_an_event_in_case_of_an_invalid_message()
    {
        $notifiable = new NotifiableWithAttribute();
        $notification = Mockery::mock(Notification::class);

        // Invalid message
        $notification->shouldReceive('toTwilio')->andReturn(-1);

        $this->dispatcher->shouldReceive('fire')
            ->atLeast()->once()
            ->with(Mockery::type(NotificationFailed::class));

        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_will_fire_an_event_in_case_of_a_missing_from_number()
    {
        $notifiable = new NotifiableWithAttribute();
        $notification = Mockery::mock(Notification::class);
        $notification->shouldReceive('toTwilio')->andReturn('Message text');

        $channel = new TwilioChannel($this->twilio, $this->dispatcher, null);

        $this->dispatcher->shouldReceive('fire')
            ->atLeast()->once()
            ->with(Mockery::type(NotificationFailed::class));

        $channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_will_fire_an_event_in_case_of_an_invalid_message_object()
    {
        $notifiable = new NotifiableWithAttribute();
        $notification = Mockery::mock(Notification::class);
        $notification->shouldReceive('toTwilio')->andReturn(new InvalidMessage());

        $this->dispatcher->shouldReceive('fire')
            ->atLeast()->once()
            ->with(Mockery::type(NotificationFailed::class));

        $this->channel->send($notifiable, $notification);
    }
}

class InvalidMessage extends TwilioMessage
{
}

class Notifiable
{
    public $phone_number = null;

    public function routeNotificationFor()
    {
    }
}

class NotifiableWithMethod
{
    public function routeNotificationFor()
    {
        return '+1111111111';
    }
}

class NotifiableWithAttribute
{
    public $phone_number = '+22222222222';

    public function routeNotificationFor()
    {
    }
}
