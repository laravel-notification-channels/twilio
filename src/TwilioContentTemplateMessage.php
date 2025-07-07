<?php

namespace NotificationChannels\Twilio;

class TwilioContentTemplateMessage extends TwilioSmsMessage
{
    /**
     * The SID of the content template (starting with H)
     */
    public ?string $contentSid;

    /**
     * The variables to replace in the content template
     */
    public string|array|null $contentVariables;

    /**
     * Set the content sid (starting with H).
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
     */
    public function contentVariables(array $contentVariables): self
    {
        $this->contentVariables = json_encode($contentVariables);

        return $this;
    }
}
