<?php

namespace NotificationChannels\Twilio\Tests\Unit;

use NotificationChannels\Twilio\TwilioCallMessage;

class TwilioCallMessageTest extends TwilioMessageTest
{
    /** @var TwilioCallMessage */
    protected $message;

    public function setUp(): void
    {
        parent::setUp();

        $this->message = new TwilioCallMessage();
    }

    /** @test */
    public function it_can_accept_a_message_when_constructing_a_message()
    {
        $message = new TwilioCallMessage('http://example.com');

        $this->assertEquals('http://example.com', $message->content);
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = TwilioCallMessage::create('http://example.com');

        $this->assertEquals('http://example.com', $message->content);
    }

    /** @test */
    public function it_can_set_the_url()
    {
        $this->message->url('http://example.com');

        $this->assertEquals('http://example.com', $this->message->content);
    }

    /** @test */
    public function it_can_set_optional_parameters()
    {
        $message = TwilioCallMessage::create('myMessage');
        $message->status(TwilioCallMessage::STATUS_CANCELED);
        $message->method('PUT');
        $message->statusCallback('http://example.com');
        $message->statusCallbackMethod('PUT');
        $message->fallbackUrl('http://example.com');
        $message->fallbackMethod('PUT');

        $this->assertEquals(TwilioCallMessage::STATUS_CANCELED, $message->status);
        $this->assertEquals('PUT', $message->method);
        $this->assertEquals('http://example.com', $message->statusCallback);
        $this->assertEquals('PUT', $message->statusCallbackMethod);
        $this->assertEquals('http://example.com', $message->fallbackUrl);
        $this->assertEquals('PUT', $message->fallbackMethod);
    }
}
