<?php

namespace NotificationChannels\Twilio\Test;

use NotificationChannels\Twilio\TwilioCallMessage;

class TwilioCallMessageTest extends TwilioMessageTest
{
    public function setUp()
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
}
