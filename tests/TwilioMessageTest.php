<?php

namespace NotificationChannels\Twilio\Test;

use NotificationChannels\Twilio\TwilioMessage;
use PHPUnit_Framework_TestCase;

abstract class TwilioMessageTest extends PHPUnit_Framework_TestCase
{
    /** @var TwilioMessage */
    protected $message;

    /** @test */
    abstract public function it_can_accept_a_message_when_constructing_a_message();

    /** @test */
    abstract public function it_provides_a_create_method();

    /** @test */
    public function it_can_set_the_content()
    {
        $this->message->content('myMessage');

        $this->assertEquals('myMessage', $this->message->content);
    }

    /** @test */
    public function it_can_set_the_from()
    {
        $this->message->from('+1234567890');

        $this->assertEquals('+1234567890', $this->message->from);
    }
}
