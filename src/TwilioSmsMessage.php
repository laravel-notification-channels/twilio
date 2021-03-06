<?php

namespace NotificationChannels\Twilio;

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
     * @var null|int
     */
    public $validityPeriod;

    /**
     * @var null|int
     * Total number of attempts made ( including this ) to send out the message regardless of the provider used.
     * Used to provide feedback of delivery quality to twilio.
     */
    public $attempt;

    /**
     * @var null|bool
     * Whether to detect Unicode characters that have a similar GSM-7 character and replace them.
     */
    public $smartEncoded;

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
     * Set the validity period (in seconds).
     *
     * @param int $validityPeriodSeconds
     * @return $this
     */
    public function validityPeriod(int $validityPeriodSeconds): self
    {
        $this->validityPeriod = $validityPeriodSeconds;

        return $this;
    }

    /**
     * Set the attempt option.
     *
     * @param int $attempt
     * @return $this
     */
    public function attempt(int $attempt): self
    {
        $this->attempt = $attempt;

        return $this;
    }

    /**
     * Set the smart encoded option.
     *
     * @param bool $smartEncoded
     * @return $this
     */
    public function smartEncoded(bool $smartEncoded): self
    {
        $this->smartEncoded = $smartEncoded;

        return $this;
    }
}
