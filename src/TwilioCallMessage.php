<?php

namespace NotificationChannels\Twilio;

use NotificationChannels\Twilio\Exceptions\InvalidConfigException;
use Twilio\TwiML\VoiceResponse;

class TwilioCallMessage extends TwilioMessage
{
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_COMPLETED = 'completed';

    public const TYPE_URL = 'url';
    public const TYPE_TWIML = 'twiml';

    /**
     * @var int
     */
    public $contentType = null;

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
        $this->contentType(self::TYPE_URL);
        $this->content = $url;

        return $this;
    }

    /**
     * Set the message twiml.
     *
     * @param  string $twiml
     * @return $this
     */
    public function twiml(VoiceResponse $response): self
    {
        $this->contentType(self::TYPE_TWIML);
        $this->content = (string) $response;

        return $this;
    }

    protected function contentType(string $contentType)
    {
        if (
            ! is_null($this->contentType)
            && $contentType !== $this->contentType
        ) {
            InvalidConfigException::multipleContentTypes();
        }

        $this->contentType = $contentType;
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
