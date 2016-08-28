<?php

namespace NotificationChannels\Twilio\Test;

use NotificationChannels\Twilio\TwilioSmsMessage;

class TwilioSmsMessageTest extends TwilioMessageTest
{
    public function setUp()
    {
        parent::setUp();

        $this->message = new TwilioSmsMessage();
    }

    /** @test */
    public function it_can_accept_a_message_when_constructing_a_message()
    {
        $message = new TwilioSmsMessage('myMessage');

        $this->assertEquals('myMessage', $message->content);
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = TwilioSmsMessage::create('myMessage');

        $this->assertEquals('myMessage', $message->content);
    }
}
