<?php

namespace NotificationChannels\Twilio\Test;

use NotificationChannels\Twilio\TwilioMmsMessage;

class TwilioMmsMessageTest extends TwilioMessageTest
{
    public function setUp()
    {
        parent::setUp();

        $this->message = new TwilioMmsMessage();
    }

    /** @test */
    public function it_can_accept_a_message_when_constructing_a_message()
    {
        $message = new TwilioMmsMessage('myMessage');

        $this->assertEquals('myMessage', $message->content);
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = TwilioMmsMessage::create('myMessage');

        $this->assertEquals('myMessage', $message->content);
    }

    /** @test */
    public function it_sets_alphanumeric_sender()
    {
        $message = TwilioMmsMessage::create('myMessage');
        $message->sender('TestSender');

        $this->assertEquals('TestSender', $message->alphaNumSender);
    }

    /** @test */
    public function it_sets_media_url()
    {
        $message = TwilioMmsMessage::create('myMessage');
        $message->mediaUrl('https://picsum.photos/300');

        $this->assertEquals('https://picsum.photos/300', $message->mediaUrl);
    }

    /** @test */
    public function it_can_return_the_alphanumeric_sender_if_set()
    {
        $message = TwilioMmsMessage::create('myMessage');
        $message->sender('TestSender');

        $this->assertEquals('TestSender', $message->getFrom());
    }

    /** @test */
    public function it_can_set_optional_parameters()
    {
        $message = TwilioMmsMessage::create('myMessage');
        $message->statusCallback('http://example.com');
        $message->statusCallbackMethod('PUT');
        $message->applicationSid('ABCD1234');
        $message->forceDelivery(true);
        $message->maxPrice(0.05);
        $message->provideFeedback(true);
        $message->validityPeriod(120);
        $message->mediaUrl('http://example.com');

        $this->assertEquals('http://example.com', $message->statusCallback);
        $this->assertEquals('PUT', $message->statusCallbackMethod);
        $this->assertEquals('ABCD1234', $message->applicationSid);
        $this->assertEquals(true, $message->forceDelivery);
        $this->assertEquals(0.05, $message->maxPrice);
        $this->assertEquals(true, $message->provideFeedback);
        $this->assertEquals(120, $message->validityPeriod);
        $this->assertEquals('http://example.com', $message->mediaUrl);
    }
}
