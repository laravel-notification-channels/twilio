<?php

namespace NotificationChannels\Twilio;

class TwilioMmsMessage extends TwilioSmsMessage
{
    /**
     * @var string|null
     */
    public $mediaUrl = null;

    /**
     * Set the message media url.
     *
     * @param string $url
     * @return $this
     */
    public function mediaUrl($url)
    {
        $this->mediaUrl = $url;

        return $this;
    }
}
