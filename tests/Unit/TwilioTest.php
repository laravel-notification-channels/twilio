<?php

namespace NotificationChannels\Twilio\Tests\Unit;

use Illuminate\Contracts\Events\Dispatcher;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\Twilio\Exceptions\CouldNotSendNotification;
use NotificationChannels\Twilio\Twilio;
use NotificationChannels\Twilio\TwilioCallMessage;
use NotificationChannels\Twilio\TwilioConfig;
use NotificationChannels\Twilio\TwilioMessage;
use NotificationChannels\Twilio\TwilioMmsMessage;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Twilio\Rest\Api\V2010\Account\CallInstance;
use Twilio\Rest\Api\V2010\Account\CallList;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Api\V2010\Account\MessageList;
use Twilio\Rest\Client as TwilioService;

class TwilioTest extends MockeryTestCase
{
    /** @var Twilio */
    protected $twilio;

    /** @var TwilioService */
    protected $twilioService;

    /** @var Dispatcher */
    protected $dispatcher;

    /**
     * @var TwilioConfig
     */
    protected $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->twilioService = Mockery::mock(TwilioService::class);
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->config = Mockery::mock(TwilioConfig::class);

        $this->twilioService->messages = Mockery::mock(MessageList::class);
        $this->twilioService->calls = Mockery::mock(CallList::class);

        $this->twilio = new Twilio($this->twilioService, $this->config);
    }

    /** @test */
    public function it_can_send_a_sms_message_to_twilio()
    {
        $message = new TwilioSmsMessage('Message text');
        $message->statusCallback('http://example.com');
        $message->statusCallbackMethod('PUT');
        $message->applicationSid('ABCD1234');
        $message->maxPrice(0.05);
        $message->provideFeedback(true);
        $message->validityPeriod(120);

        $this->config->shouldReceive('getFrom')
            ->once()
            ->andReturn('+1234567890');

        $this->config->shouldReceive('getDebugTo')
            ->once()
            ->andReturn(null);

        $this->config->shouldReceive('getServiceSid')
            ->once()
            ->andReturn(null);

        $this->twilioService->messages->shouldReceive('create')
            ->atLeast()->once()
            ->with('+1111111111', [
                'from' => '+1234567890',
                'body' => 'Message text',
                'statusCallback' => 'http://example.com',
                'statusCallbackMethod' => 'PUT',
                'applicationSid' => 'ABCD1234',
                'maxPrice' => 0.05,
                'provideFeedback' => true,
                'validityPeriod' => 120,
            ])
            ->andReturn(Mockery::mock(MessageInstance::class));

        $this->twilio->sendMessage($message, '+1111111111');
    }

    /** @test */
    public function it_can_send_a_mms_message_to_twilio()
    {
        $message = new TwilioMmsMessage('Message text');
        $message->statusCallback('http://example.com');
        $message->statusCallbackMethod('PUT');
        $message->applicationSid('ABCD1234');
        $message->maxPrice(0.05);
        $message->provideFeedback(true);
        $message->validityPeriod(120);
        $message->mediaUrl('http://example.com');

        $this->config->shouldReceive('getFrom')
            ->once()
            ->andReturn('+1234567890');

        $this->config->shouldReceive('getServiceSid')
            ->once()
            ->andReturn(null);

        $this->config->shouldReceive('getDebugTo')
            ->once()
            ->andReturn(null);

        $this->twilioService->messages->shouldReceive('create')
            ->atLeast()->once()
            ->with('+1111111111', [
                'from' => '+1234567890',
                'body' => 'Message text',
                'statusCallback' => 'http://example.com',
                'statusCallbackMethod' => 'PUT',
                'applicationSid' => 'ABCD1234',
                'maxPrice' => 0.05,
                'provideFeedback' => true,
                'validityPeriod' => 120,
                'mediaUrl' => 'http://example.com',
            ])
            ->andReturn(Mockery::mock(MessageInstance::class));

        $this->twilio->sendMessage($message, '+1111111111');
    }

    /** @test */
    public function it_can_send_a_sms_message_to_twilio_with_alphanumeric_sender()
    {
        $message = new TwilioSmsMessage('Message text');

        $this->config->shouldReceive('getAlphanumericSender')
            ->once()
            ->andReturn('TwilioTest');

        $this->config->shouldNotReceive('getFrom');

        $this->config->shouldReceive('getServiceSid')
            ->once()
            ->andReturn(null);

        $this->config->shouldReceive('getDebugTo')
            ->once()
            ->andReturn(null);

        $this->twilioService->messages->shouldReceive('create')
            ->atLeast()->once()
            ->with('+1111111111', [
                'from' => 'TwilioTest',
                'body' => 'Message text',
            ])
            ->andReturn(Mockery::mock(MessageInstance::class));

        $this->twilio->sendMessage($message, '+1111111111', true);
    }

    /** @test */
    public function it_can_send_a_sms_message_to_twilio_with_messaging_service()
    {
        $message = new TwilioSmsMessage('Message text');

        $this->config->shouldReceive('getFrom')
            ->once()
            ->andReturn('+1234567890');

        $this->config->shouldReceive('getServiceSid')
            ->once()
            ->andReturn('service_sid');

        $this->config->shouldReceive('getDebugTo')
            ->once()
            ->andReturn(null);

        $this->twilioService->messages->shouldReceive('create')
            ->atLeast()->once()
            ->with('+1111111111', [
                'from' => '+1234567890',
                'body' => 'Message text',
                'messagingServiceSid' => 'service_sid',
            ])
            ->andReturn(Mockery::mock(MessageInstance::class));

        $this->twilio->sendMessage($message, '+1111111111');
    }

    /** @test */
    public function it_can_schedule_a_sms_message_to_twilio_with_messaging_service()
    {
        $message = new TwilioSmsMessage('Message text');

        $this->config->shouldReceive('getFrom')
            ->once()
            ->andReturn('+1234567890');

        $this->config->shouldReceive('getServiceSid')
            ->once()
            ->andReturn('service_sid');

        $this->config->shouldReceive('getDebugTo')
            ->once()
            ->andReturn(null);

        $this->twilioService->messages->shouldReceive('create')
            ->atLeast()->once()
            ->with('+1111111111', [
                'from' => '+1234567890',
                'body' => 'Message text',
                'messagingServiceSid' => 'service_sid',
                "sendAt" => new \DateTime('+30 min'),
                "scheduleType" => "fixed"
            ])
            ->andReturn(Mockery::mock(MessageInstance::class));

        $this->twilio->sendMessage($message, '+1111111111');
    }

    /** @test */
    public function it_can_send_a_call_to_twilio()
    {
        $message = new TwilioCallMessage('http://example.com');
        $message->from = '+2222222222';
        $message->status(TwilioCallMessage::STATUS_CANCELED);
        $message->method('PUT');
        $message->statusCallback('http://example.com');
        $message->statusCallbackMethod('PUT');
        $message->fallbackUrl('http://example.com');
        $message->fallbackMethod('PUT');

        $this->twilioService->calls->shouldReceive('create')
            ->atLeast()->once()
            ->with('+1111111111', '+2222222222', [
                'url' => 'http://example.com',
                'status' => TwilioCallMessage::STATUS_CANCELED,
                'method' => 'PUT',
                'statusCallback' => 'http://example.com',
                'statusCallbackMethod' => 'PUT',
                'fallbackUrl' => 'http://example.com',
                'fallbackMethod' => 'PUT',
            ])
            ->andReturn(Mockery::mock(CallInstance::class));

        $this->twilio->sendMessage($message, '+1111111111');
    }

    /** @test */
    public function it_will_throw_an_exception_in_case_of_a_missing_from_number()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Notification was not sent. Missing `from` number.');

        $smsMessage = new TwilioSmsMessage('Message text');

        $this->config->shouldReceive('getDebugTo')
            ->once()
            ->andReturn(null);

        $this->config->shouldReceive('getFrom')
            ->once()
            ->andReturn(null);

        $this->config->shouldReceive('getServiceSid')
            ->once()
            ->andReturn(null);

        $this->twilio->sendMessage($smsMessage, null);
    }

    /** @test */
    public function it_will_throw_an_exception_in_case_of_an_unrecognized_message_object()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Notification was not sent. Message object class');

        $this->twilio->sendMessage(new InvalidMessage(), null);
    }

    /** @test */
    public function it_should_use_universal_to()
    {
        $debugTo = '+1222222222';

        $message = new TwilioSmsMessage('Message text');
        $message->statusCallback('http://example.com');
        $message->statusCallbackMethod('PUT');
        $message->applicationSid('ABCD1234');
        $message->maxPrice(0.05);
        $message->provideFeedback(true);
        $message->validityPeriod(120);

        $this->config->shouldReceive('getFrom')
            ->once()
            ->andReturn('+1234567890');

        $this->config->shouldReceive('getDebugTo')
            ->once()
            ->andReturn($debugTo);

        $this->config->shouldReceive('getServiceSid')
            ->once()
            ->andReturn(null);

        $this->twilioService->messages->shouldReceive('create')
            ->atLeast()->once()
            ->with($debugTo, [
                'from' => '+1234567890',
                'body' => 'Message text',
                'statusCallback' => 'http://example.com',
                'statusCallbackMethod' => 'PUT',
                'applicationSid' => 'ABCD1234',
                'maxPrice' => 0.05,
                'provideFeedback' => true,
                'validityPeriod' => 120,
            ])
            ->andReturn(Mockery::mock(MessageInstance::class));

        $this->twilio->sendMessage($message, '+1111111111');
    }
}

class InvalidMessage extends TwilioMessage
{
}
