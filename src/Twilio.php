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
     * @param TwilioConfig   $config
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
     * @param  string        $to
     * @param bool           $useAlphanumericSender
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function sendMessage(TwilioMessage $message, $to, $useAlphanumericSender = false)
    {
        if ($message instanceof TwilioSmsMessage) {
            if ($useAlphanumericSender && $sender = $this->getAlphanumericSender()) {
                $message->from($sender);
            }

            return $this->sendSmsMessage($message, $to);
        }
       
        if ($message instanceof TwilioNotifyMessage) {
            return $this->notifyMessage($message, $to);
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
     * @param string           $to
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance
     */
    protected function sendSmsMessage(TwilioSmsMessage $message, $to)
    {
        $params = [
            'from' => $this->getFrom($message),
            'body' => trim($message->content),
        ];

        if ($service_sid = $this->config->getServiceSid()) {
            $params['messagingServiceSid'] = $service_sid;
        }

        return $this->twilioService->messages->create($to, $params);
    }

    /**
     * Send an notify message uing the Twilio service
     *
     * @param TwilioNotifyMessage $message
     * @param string              $to
     * @return \Twilio
     */
    protected function notifyMessage(TwilioNotifyMessage $message, $to)
    {
        $params = [
            "toBinding" => '{"binding_type":"sms", "address":"'. $to .'"}',
            'body' => trim($message->content)
        ];

        $service_sid = $this->getServiceSid($message);

        return $this->twilioService
                ->notify->services($service_sid)
                ->notifications->create($params);
    }

    /**
     * Make a call using the Twilio Service.
     *
     * @param TwilioCallMessage $message
     * @param string            $to
     * @return \Twilio\Rest\Api\V2010\Account\CallInstance
     */
    protected function makeCall(TwilioCallMessage $message, $to)
    {
        return $this->twilioService->calls->create(
            $to,
            $this->getFrom($message),
            ['url' => trim($message->content)]
        );
    }

    /**
     * Get the from address from message, or config.
     *
     * @param TwilioMessage $message
     * @return string
     * @throws CouldNotSendNotification
     */
    protected function getFrom(TwilioMessage $message)
    {
        if (! $from = $message->getFrom() ?: $this->config->getFrom()) {
            throw CouldNotSendNotification::missingFrom();
        }

        return $from;
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
     * get service sid
     *
     * @param TwilioMessage $message
     * @return string
     * @throws CouldNotSendNotification
     */
    protected function getServiceSid(TwilioMessage $message)
    {
        if (! $service_sid = $message->getServiceSid()) {
            if (! $service_sid = $this->config->getServiceSid()) {
                throw CouldNotSendNotification::missingServiceSid();
            }
        }

        return $service_sid;
    }
}
