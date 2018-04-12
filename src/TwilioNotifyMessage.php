<?php

namespace NotificationChannels\Twilio;

class TwilioNotifyMessage extends TwilioMessage
{

    /**
     * service sid
     *
     * @var null|string
     */
    public $service_sid = null;

    /**
     * get Service Id
     *
     * @return mixed
     */
    public function getServiceSid()
    {
        return $this->service_sid;
    }

    /**
     * set Service Id
     *
     * @param string $service_id
     * @return $this
     */
    public function setServiceSid($service_sid)
    {
        $this->service_sid = $service_sid;

        return $this;
    }
}
