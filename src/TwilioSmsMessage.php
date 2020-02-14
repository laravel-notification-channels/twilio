<?php

namespace NotificationChannels\Twilio;

class TwilioSmsMessage extends TwilioMessage
{
    /**
     * @var null|string
     */
    public $alphaNumSender = null;

    /**
     * @var null|string
     */
    public $messagingServiceSid = null;

    /**
     * @var null|string
     */
    public $applicationSid = null;

    /**
     * @var null|bool
     */
    public $forceDelivery = null;

    /**
     * @var null|float
     */
    public $maxPrice = null;

    /**
     * @var null|bool
     */
    public $provideFeedback = null;

    /**
     * @var null|int
     */
    public $validityPeriod = null;

    /**
     * Get the from address of this message.
     *
     * @return null|string
     */
    public function getFrom()
    {
        if ($this->from) {
            return $this->from;
        }

        if ($this->alphaNumSender && strlen($this->alphaNumSender) > 0) {
            return $this->alphaNumSender;
        }
    }

    /**
     * Set the messaging service SID.
     *
     * @param  string $messagingServiceSid
     * @return $this
     */
    public function messagingServiceSid($messagingServiceSid)
    {
        $this->messagingServiceSid = $messagingServiceSid;

        return $this;
    }

    /**
     * Get the messaging service SID of this message.
     *
     * @return null|string
     */
    public function getMessagingServiceSid()
    {
        return $this->messagingServiceSid;
    }

    /**
     * Set the alphanumeric sender.
     *
     * @param string $sender
     * @return $this
     */
    public function sender($sender)
    {
        $this->alphaNumSender = $sender;

        return $this;
    }

    /**
     * Set application SID for the message status callback.
     *
     * @param string $applicationSid
     * @return $this
     */
    public function applicationSid($applicationSid)
    {
        $this->applicationSid = $applicationSid;

        return $this;
    }

    /**
     * Set force delivery (Deliver message without validation).
     *
     * @param bool $forceDelivery
     * @return $this
     */
    public function forceDelivery($forceDelivery)
    {
        $this->forceDelivery = $forceDelivery;

        return $this;
    }

    /**
     * Set the max price (in USD dollars).
     *
     * @param float $maxPrice
     * @return $this
     */
    public function maxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Set the provide feedback option.
     *
     * @param bool $provideFeedback
     * @return $this
     */
    public function provideFeedback($provideFeedback)
    {
        $this->provideFeedback = $provideFeedback;

        return $this;
    }

    /**
     * Set the validity period (in seconds).
     *
     * @param int $validityPeriod
     * @return $this
     */
    public function validityPeriod($validityPeriod)
    {
        $this->validityPeriod = $validityPeriod;

        return $this;
    }
}
