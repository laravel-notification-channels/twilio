<?php

namespace NotificationChannels\Twilio;

use NotificationChannels\Twilio\Exceptions\CouldNotSendNotification;
use Twilio\Rest\Client as TwilioService;

class Twilio
{
    /**
     * @var TwilioService
     */
    protected $twilioService;

    /**
     * @var TwilioConfig
     */
    private $config;

    /**
     * Twilio constructor.
     *
     * @param  TwilioService $twilioService
     * @param TwilioConfig $config
     */
    public function __construct(TwilioService $twilioService, TwilioConfig $config)
    {
        $this->twilioService = $twilioService;
        $this->config = $config;
    }

    /**
     * Send a TwilioMessage to the a phone number.
     *
     * @param  TwilioMessage $message
     * @param  string $to
     * @param bool $useAlphanumericSender
     * @return mixed
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function sendMessage(TwilioMessage $message, $to, $useAlphanumericSender = false)
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
     * @param string $to
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance
     */
    protected function sendSmsMessage(TwilioSmsMessage $message, $to)
    {
        $params = [
            'body' => trim($message->content),
        ];

        if ($messagingServiceSid = $this->getMessagingServiceSid($message)) {
            $params['messagingServiceSid'] = $messagingServiceSid;
        }

        if ($from = $this->getFrom($message)) {
            $params['from'] = $from;
        }

        if (! $from && ! $messagingServiceSid) {
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
     * @param string $to
     * @return \Twilio\Rest\Api\V2010\Account\CallInstance
     * @throws \Twilio\Exceptions\TwilioException
     */
    protected function makeCall(TwilioCallMessage $message, $to)
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
     * @return string
     */
    protected function getFrom(TwilioMessage $message)
    {
        return $message->getFrom() ?: $this->config->getFrom();
    }

    /**
     * Get the messaging service SID from message, or config.
     *
     * @param TwilioSmsMessage $message
     * @return string
     */
    protected function getMessagingServiceSid(TwilioSmsMessage $message)
    {
        return $message->getMessagingServiceSid() ?: $this->config->getServiceSid();
    }

    /**
     * Get the alphanumeric sender from config, if one exists.
     *
     * @return string|null
     */
    protected function getAlphanumericSender()
    {
        if ($sender = $this->config->getAlphanumericSender()) {
            return $sender;
        }
    }

    /**
     * @param array $params
     * @param TwilioMessage $message
     * @param array $optionalParams
     * @return Twilio
     */
    protected function fillOptionalParams(&$params, $message, $optionalParams)
    {
        foreach ($optionalParams as $optionalParam) {
            if ($message->$optionalParam) {
                $params[$optionalParam] = $message->$optionalParam;
            }
        }

        return $this;
    }
}
