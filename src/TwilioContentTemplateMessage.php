<?php

namespace NotificationChannels\Twilio;

class TwilioContentTemplateMessage extends TwilioSmsMessage
{
    /**
     * The SID of the content template (starting with H)
     * @var null|string
     */
    public $contentSid;

    /**
     * The variables to replace in the content template
     * @var null|array|string
     */
    public $contentVariables;

    /**
     * Set the content sid (starting with H).
     *
     * @param  string $contentSid
     * @return $this
     */
    public function contentSid(string $contentSid): self
    {
        $this->contentSid = $contentSid;

        return $this;
    }

    /**
     * Set the content variables.
     *
     * @param  array $contentVariables The variables to replace in the content template (i.e. ['1' => 'John Doe'])
     * @return $this
     */
    public function contentVariables(array $contentVariables): self
    {
        $this->contentVariables = json_encode($contentVariables);

        return $this;
    }
}
