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
     * For Content Editor/API only: Key-value pairs of Template variables and their substitution values.
     * content_sid parameter must also be provided.
     * If values are not defined in the content_variables parameter, the Template's default placeholder values are used.
     *
     * @param  array $contentVariables The variables to replace in the content template (i.e. ['1' => 'John Doe'])
     */
    public function contentVariables(array $contentVariables): self
    {
        $this->contentVariables = json_encode($contentVariables);

        return $this;
    }
}
