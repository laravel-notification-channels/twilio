<?php

namespace NotificationChannels\Twilio;

class TwilioSmsMessage extends TwilioMessage
{
    public ?string $alphaNumSender = null;

    public ?string $messagingServiceSid = null;

    public ?string $applicationSid = null;

    public ?bool $forceDelivery = null;

    public ?float $maxPrice = null;

    public ?bool $provideFeedback = null;

    public ?int $validityPeriod = null;

    public ?int $attempt = null;

    public ?string $contentRetention = null;

    public ?string $addressRetention = null;

    public ?bool $smartEncoded = null;

    public ?array $persistentAction = null;

    public ?string $scheduleType = null;

    public ?string $sendAt = null;

    public ?bool $sendAsMms = null;

    public ?string $riskCheck = null;

    /**
     * Get the from address of this message.
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
     */
    public function messagingServiceSid(string $messagingServiceSid): self
    {
        $this->messagingServiceSid = $messagingServiceSid;

        return $this;
    }

    /**
     * Get the messaging service SID of this message.
     */
    public function getMessagingServiceSid(): ?string
    {
        return $this->messagingServiceSid;
    }

    /**
     * Set the alphanumeric sender.
     */
    public function sender(string $sender): self
    {
        $this->alphaNumSender = $sender;

        return $this;
    }

    /**
     * Set application SID for the message status callback.
     */
    public function applicationSid(string $applicationSid): self
    {
        $this->applicationSid = $applicationSid;

        return $this;
    }

    /**
     * Set force delivery (Deliver message without validation).
     */
    public function forceDelivery(bool $forceDelivery): self
    {
        $this->forceDelivery = $forceDelivery;

        return $this;
    }

    /**
     * Set the max price (in USD dollars).
     */
    public function maxPrice(float $maxPrice): self
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Set the provide feedback option.
     */
    public function provideFeedback(bool $provideFeedback): self
    {
        $this->provideFeedback = $provideFeedback;

        return $this;
    }

    /**
     * Set the validity period (in seconds).
     */
    public function validityPeriod(int $validityPeriodSeconds): self
    {
        $this->validityPeriod = $validityPeriodSeconds;

        return $this;
    }

    /**
     * Total number of attempts made to send the message (including the current one).
     */
    public function attempt(int $attempt): self
    {
        $this->attempt = $attempt;

        return $this;
    }

    /**
     * Determines if the message content can be stored or redacted based on privacy settings
     * Possible values:
     * - retain
     * - discard
     */
    public function contentRetention(string $contentRetention): self
    {
        $this->contentRetention = $contentRetention;

        return $this;
    }

    /**
     * Determines if the address can be stored or obfuscated based on privacy settings
     * Possible values:
     * - retain
     * - obfuscate
     */
    public function addressRetention(string $addressRetention): self
    {
        $this->addressRetention = $addressRetention;

        return $this;
    }

    /**
     * Whether to detect Unicode characters that have a similar GSM-7 character and replace them
     */
    public function smartEncoded(bool $smartEncoded): self
    {
        $this->smartEncoded = $smartEncoded;

        return $this;
    }

    /**
     * Rich actions for non-SMS/MMS channels. Used for sending location in WhatsApp messages.
     * @param array<string> $persistentAction
     * @return $this
     */
    public function persistentAction(array $persistentAction): self
    {
        $this->persistentAction = $persistentAction;

        return $this;
    }

    /**
     * For Messaging Services only: Include this parameter with a value of fixed in conjunction with the send_time parameter in order to schedule a Message.
     * Possible values:
     * - fixed
     */
    public function scheduleType(string $scheduleType): self
    {
        $this->scheduleType = $scheduleType;

        return $this;
    }

    /**
     * The time that Twilio will send the message.
     * Must be in ISO 8601 format.
     */
    public function sendAt(string $sendAt): self
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * If set to true, Twilio delivers the message as a single MMS message, regardless of the presence of media.
     */
    public function sendAsMms(bool $sendAsMms): self
    {
        $this->sendAsMms = $sendAsMms;

        return $this;
    }

    /**
     * Include this parameter with a value of "disable" to skip any kind of risk check on the respective message request.
     * Possible values:
     * - enable (default)
     * - disable
     */
    public function riskCheck(string $riskCheck): self
    {
        $this->riskCheck = $riskCheck;

        return $this;
    }
}
