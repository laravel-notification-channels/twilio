<?php

namespace NotificationChannels\Twilio;

class TwilioConfig
{
    /** @var array */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function usingUsernamePasswordAuth(): bool
    {
        return $this->getUsername() !== null && $this->getPassword() !== null && $this->getAccountSid() !== null;
    }

    public function usingTokenAuth(): bool
    {
        return $this->getAuthToken() !== null && $this->getAccountSid() !== null;
    }

    public function getAuthToken(): ?string
    {
        return $this->config['auth_token'] ?? null;
    }

    public function getUsername(): ?string
    {
        return $this->config['username'] ?? null;
    }

    public function getPassword(): ?string
    {
        return $this->config['password'] ?? null;
    }

    public function getAccountSid(): ?string
    {
        return $this->config['account_sid'] ?? null;
    }

    public function getFrom(): ?string
    {
        return $this->config['from'] ?? null;
    }

    public function getAlphanumericSender(): ?string
    {
        return $this->config['alphanumeric_sender'] ?? null;
    }

    public function getServiceSid(): ?string
    {
        return $this->config['sms_service_sid'] ?? null;
    }

    public function getDebugTo(): ?string
    {
        return $this->config['debug_to'] ?? null;
    }

    public function getIgnoredErrorCodes(): array
    {
        return $this->config['ignored_error_codes'] ?? [];
    }

    public function isIgnoredErrorCode(int $code): bool
    {
        if (in_array('*', $this->getIgnoredErrorCodes(), true)) {
            return true;
        }

        return in_array($code, $this->getIgnoredErrorCodes(), true);
    }

    public function isShortenUrlsEnabled(): bool
    {
        return $this->config['shorten_urls'] ?? false;
    }
}
