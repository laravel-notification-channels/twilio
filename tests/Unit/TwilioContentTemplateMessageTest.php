<?php

namespace NotificationChannels\Twilio\Tests\Unit;

use NotificationChannels\Twilio\TwilioContentTemplateMessage;
use PHPUnit\Framework\Attributes\Test;

class TwilioContentTemplateMessageTest extends TwilioMessageTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->message = new TwilioContentTemplateMessage;
    }

    #[Test]
    public function it_can_accept_a_message_when_constructing_a_message()
    {
        $message = new TwilioContentTemplateMessage('myMessage');

        $this->assertEquals('myMessage', $message->content);
    }

    #[Test]
    public function it_provides_a_create_method()
    {
        $message = TwilioContentTemplateMessage::create('myMessage');

        $this->assertEquals('myMessage', $message->content);
    }

    #[Test]
    public function it_sets_alphanumeric_sender()
    {
        $message = TwilioContentTemplateMessage::create('myMessage');
        $message->sender('TestSender');

        $this->assertEquals('TestSender', $message->alphaNumSender);
    }

    #[Test]
    public function it_sets_content_sid()
    {
        $message = TwilioContentTemplateMessage::create('myMessage');
        $message->contentSid('HXXXXXXXXXXXXXXXXXXXXXXXX');

        $this->assertEquals('HXXXXXXXXXXXXXXXXXXXXXXXX', $message->contentSid);
    }

    #[Test]
    public function it_sets_content_variables()
    {
        $message = TwilioContentTemplateMessage::create('myMessage');
        $message->contentVariables([
            '1' => 'John Doe',
            '2' => 'ACME Inc.',
        ]);

        $this->assertEquals('{"1":"John Doe","2":"ACME Inc."}', $message->contentVariables);
    }
}
