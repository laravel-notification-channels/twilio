<?php

namespace NotificationChannels\Twilio;

class TwilioCallMessage extends TwilioMessage
{
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_COMPLETED = 'completed';

    /**
     * @var null|string
     */
    public $method;

    /**
     * @var null|string
     */
    public $status;

    /**
     * @var null|string
     */
    public $fallbackUrl;

    /**
     * @var null|string
     */
    public $fallbackMethod;

    /**
     * Set the message url.
     *
     * @param  string $url
     * @return $this
     */
    public function url(string $url): self
    {
        $this->content = $url;

        return $this;
    }

    /**
     * Set the message url request method.
     *
     * @param  string $method
     * @return $this
     */
    public function method($method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Set the status for the current calls.
     *
     * @param  string $status
     * @return $this
     */
    public function status(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set the fallback url.
     *
     * @param string $fallbackUrl
     * @return $this
     */
    public function fallbackUrl(string $fallbackUrl): self
    {
        $this->fallbackUrl = $fallbackUrl;

        return $this;
    }

    /**
     * Set the fallback url request method.
     *
     * @param string $fallbackMethod
     * @return $this
     */
    public function fallbackMethod(string $fallbackMethod): self
    {
        $this->fallbackMethod = $fallbackMethod;

        return $this;
    }
}
