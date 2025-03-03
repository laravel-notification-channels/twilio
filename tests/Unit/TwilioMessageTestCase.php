<?php

namespace NotificationChannels\Twilio\Tests\Unit;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\Twilio\TwilioMessage;
use PHPUnit\Framework\Attributes\Test;

abstract class TwilioMessageTestCase extends MockeryTestCase
{
    /** @var TwilioMessage */
    protected $message;

    #[Test]
    abstract public function it_can_accept_a_message_when_constructing_a_message();

    #[Test]
    abstract public function it_provides_a_create_method();

    #[Test]
    public function it_can_set_the_content()
    {
        $this->message->content('myMessage');

        $this->assertEquals('myMessage', $this->message->content);
    }

    #[Test]
    public function it_can_set_the_from()
    {
        $this->message->from('+1234567890');

        $this->assertEquals('+1234567890', $this->message->from);
    }

    #[Test]
    public function it_can_return_the_from_using_getter()
    {
        $this->message->from('+1234567890');

        $this->assertEquals('+1234567890', $this->message->getFrom());
    }
}
