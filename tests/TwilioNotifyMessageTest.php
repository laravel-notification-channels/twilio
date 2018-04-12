<?php

namespace NotificationChannels\Twilio\Test;

use NotificationChannels\Twilio\TwilioNotifyMessage;

class TwilioNotifyMessageTest extends TwilioMessageTest
{
    public function setUp()
    {
        parent::setUp();

        $this->message = new TwilioNotifyMessage();
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = TwilioNotifyMessage::create('myMessage');

        $this->assertEquals('myMessage', $message->content);
    }

    /** @test */
    public function it_can_return_the_service_sid_if_set()
    {
        $message = TwilioNotifyMessage::create('myMessage');
        $message->setServiceSid('TestServiceSid');

        $this->assertEquals('TestServiceSid', $message->getServiceSid());
    }
}
