<?php

namespace NotificationChannels\Twilio;

use Illuminate\Support\Arr;

class TwilioConfig
{
    /**
     * @var array
     */
    private $config;

    /**
     * TwilioConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the auth token.
     *
     * @return string
     */
    public function getAuthToken()
    {
        return $this->config['auth_token'];
    }

    /**
     * Get the username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->config['username'];
    }

    /**
     * Get the password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->config['password'];
    }

    /**
     * Get the account sid.
     *
     * @return string
     */
    public function getAccountSid()
    {
        return $this->config['account_sid'];
    }

    /**
     * Get the default from address.
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->config['from'];
    }

    /**
     * Get the alphanumeric sender.
     *
     * @return string|null
     */
    public function getAlphanumericSender()
    {
        if (isset($this->config['alphanumeric_sender'])) {
            return $this->config['alphanumeric_sender'];
        }

        return null;
    }

    /**
     * Get the service sid.
     *
     * @return string|null
     */
    public function getServiceSid()
    {
        if (isset($this->config['sms_service_sid'])) {
            return $this->config['sms_service_sid'];
        }

        return null;
    }

    /**
     * Returns universal phone number, which should receive all outgoing messages and calls.
     *
     * @return string|null
     */
    public function getTo()
    {
        return Arr::get($this->config, 'to');
    }
}
