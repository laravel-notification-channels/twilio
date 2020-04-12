<?php

namespace NotificationChannels\Twilio;

use NotificationChannels\Twilio\Exceptions\CouldNotSendNotification;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\CallInstance;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client as TwilioService;

class Twilio
{
    /** @var TwilioService */
    protected $twilioService;

    /** @var TwilioConfig */
    public $config;

    public function __construct(TwilioService $twilioService, TwilioConfig $config)
    {
        $this->twilioService = $twilioService;
        $this->config = $config;
    }

    /**
     * Send a TwilioMessage to the a phone number.
     *
     * @param TwilioMessage $message
     * @param string|null $to
     * @param bool $useAlphanumericSender
     *
     * @return mixed
     * @throws TwilioException
     * @throws CouldNotSendNotification
     */
    public function sendMessage(TwilioMessage $message, ?string $to, bool $useAlphanumericSender = false)
    {
        if ($message instanceof TwilioSmsMessage) {
            if ($useAlphanumericSender && $sender = $this->getAlphanumericSender()) {
                $message->from($sender);
            }

            return $this->sendSmsMessage($message, $to);
        }

        if ($message instanceof TwilioCallMessage) {
            return $this->makeCall($message, $to);
        }

        throw CouldNotSendNotification::invalidMessageObject($message);
    }

    /**
     * Send an sms message using the Twilio Service.
     *
     * @param TwilioSmsMessage $message
     * @param string|null $to
     *
     * @return MessageInstance
     * @throws CouldNotSendNotification
     * @throws TwilioException
     */
    protected function sendSmsMessage(TwilioSmsMessage $message, ?string $to): MessageInstance
    {
        $debugTo = $this->config->getDebugTo();

        if ($debugTo !== null) {
            $to = $debugTo;
        }

        $params = [
            'body' => trim($message->content),
        ];

        if ($messagingServiceSid = $this->getMessagingServiceSid($message)) {
            $params['messagingServiceSid'] = $messagingServiceSid;
        }

        if ($from = $this->getFrom($message)) {
            $params['from'] = $from;
        }

        if (empty($from) && empty($messagingServiceSid)) {
            throw CouldNotSendNotification::missingFrom();
        }

        $this->fillOptionalParams($params, $message, [
            'statusCallback',
            'statusCallbackMethod',
            'applicationSid',
            'forceDelivery',
            'maxPrice',
            'provideFeedback',
            'validityPeriod',
        ]);

        if ($message instanceof TwilioMmsMessage) {
            $this->fillOptionalParams($params, $message, [
                'mediaUrl',
            ]);
        }

        return $this->twilioService->messages->create($to, $params);
    }

    /**
     * Make a call using the Twilio Service.
     *
     * @param TwilioCallMessage $message
     * @param string|null $to
     *
     * @return CallInstance
     * @throws TwilioException
     * @throws CouldNotSendNotification
     */
    protected function makeCall(TwilioCallMessage $message, ?string $to): CallInstance
    {
        $params = [
            'url' => trim($message->content),
        ];

        $this->fillOptionalParams($params, $message, [
            'statusCallback',
            'statusCallbackMethod',
            'method',
            'status',
            'fallbackUrl',
            'fallbackMethod',
        ]);

        if (! $from = $this->getFrom($message)) {
            throw CouldNotSendNotification::missingFrom();
        }

        return $this->twilioService->calls->create(
            $to,
            $from,
            $params
        );
    }

    /**
     * Get the from address from message, or config.
     *
     * @param TwilioMessage $message
     * @return string|null
     */
    protected function getFrom(TwilioMessage $message): ?string
    {
        return $message->getFrom() ?: $this->config->getFrom();
    }

    /**
     * Get the messaging service SID from message, or config.
     *
     * @param TwilioSmsMessage $message
     * @return string|null
     */
    protected function getMessagingServiceSid(TwilioSmsMessage $message): ?string
    {
        return $message->getMessagingServiceSid() ?: $this->config->getServiceSid();
    }

    /**
     * Get the alphanumeric sender from config, if one exists.
     *
     * @return string|null
     */
    protected function getAlphanumericSender(): ?string
    {
        return $this->config->getAlphanumericSender();
    }

    /**
     * @param array $params
     * @param TwilioMessage $message
     * @param array $optionalParams
     * @return Twilio
     */
    protected function fillOptionalParams(&$params, $message, $optionalParams): self
    {
        foreach ($optionalParams as $optionalParam) {
            if ($message->$optionalParam) {
                $params[$optionalParam] = $message->$optionalParam;
            }
        }

        return $this;
    }
}
