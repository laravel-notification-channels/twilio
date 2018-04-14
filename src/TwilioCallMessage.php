<?php

namespace NotificationChannels\Twilio;

class TwilioCallMessage extends TwilioMessage
{
    const STATUS_CANCELED = 'canceled';
    const STATUS_COMPLETED = 'completed';

    /**
     * @var null|string
     */
    public $method = null;

    /**
     * @var null|string
     */
    public $status = null;

    /**
     * @var null|string
     */
    public $fallbackUrl = null;

    /**
     * @var null|string
     */
    public $fallbackMethod = null;

    /**
     * Set the message url.
     *
     * @param  string $url
     * @return $this
     */
    public function url($url)
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
    public function method($method)
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
    public function status($status)
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
    public function fallbackUrl($fallbackUrl)
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
    public function fallbackMethod($fallbackMethod)
    {
        $this->fallbackMethod = $fallbackMethod;

        return $this;
    }
}
