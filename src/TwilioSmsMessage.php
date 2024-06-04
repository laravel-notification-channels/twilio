<?php

namespace NotificationChannels\Twilio;

use DateTime;
use Illuminate\Support\Facades\Date;

class TwilioSmsMessage extends TwilioMessage
{
    /**
     * @var null|string
     */
    public $alphaNumSender;

    /**
     * @var null|string
     */
    public $messagingServiceSid;

    /**
     * @var null|string
     */
    public $applicationSid;

    /**
     * @var null|bool
     */
    public $forceDelivery;

    /**
     * @var null|float
     */
    public $maxPrice;

    /**
     * @var null|bool
     */
    public $provideFeedback;

    /**
     * @var null|DateTime|string
     */
    public $sendAt;

    /**
     * @var null|int
     */
    public $validityPeriod;

    /**
     * Get the from address of this message.
     *
     * @return null|string
     */
    public function getFrom(): ?string
    {
        if ($this->from) {
            return $this->from;
        }

        if ($this->alphaNumSender !== null && $this->alphaNumSender !== '') {
            return $this->alphaNumSender;
        }

        return null;
    }

    /**
     * Set the messaging service SID.
     *
     * @param  string $messagingServiceSid
     * @return $this
     */
    public function messagingServiceSid(string $messagingServiceSid): self
    {
        $this->messagingServiceSid = $messagingServiceSid;

        return $this;
    }

    /**
     * Get the messaging service SID of this message.
     *
     * @return null|string
     */
    public function getMessagingServiceSid(): ?string
    {
        return $this->messagingServiceSid;
    }

    /**
     * Set the alphanumeric sender.
     *
     * @param string $sender
     * @return $this
     */
    public function sender(string $sender): self
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
    public function applicationSid(string $applicationSid): self
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
    public function forceDelivery(bool $forceDelivery): self
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
    public function maxPrice(float $maxPrice): self
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
    public function provideFeedback(bool $provideFeedback): self
    {
        $this->provideFeedback = $provideFeedback;

        return $this;
    }

    /**
     * Set the date and time at which the message will be sent.
     */
    public function sendAt(DateTime $sendAt): self
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * Get sendAt of this message.
     */
    public function getSendAt(): ?DateTime
    {
        return $this->sendAt;
    }

    /**
     * Set the validity period (in seconds).
     *
     * @param int $validityPeriodSeconds
     *
     * @return $this
     */
    public function validityPeriod(int $validityPeriodSeconds): self
    {
        $this->validityPeriod = $validityPeriodSeconds;

        return $this;
    }
}
