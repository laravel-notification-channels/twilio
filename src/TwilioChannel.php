<?php

namespace NotificationChannels\Twilio;

use Exception;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\Exceptions\CouldNotSendNotification;
use Services_Twilio;

class TwilioChannel
{
    /** @var \Services_Twilio */
    protected $twilio;

    public function __construct(Services_Twilio $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('twilio')) {
            return;
        }

        $message = $notification->toTwilio($notifiable);

        if (is_string($message)) {
            $message = new TwilioSmsMessage($message);
        }

        if (! in_array(get_class($message), [TwilioSmsMessage::class, TwilioCallMessage::class])) {
            throw CouldNotSendNotification::invalidMessageObject($message);
        }

        if (! $from = $message->from ?: config('services.twilio.from')) {
            throw CouldNotSendNotification::missingFrom();
        }

        $response = null;

        try {
            $response = $this->sendMessage($message, $from, $to);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnException($exception);
        }
    }

    /**
     * @param $message
     * @param $from
     * @param $to
     * @return mixed
     *
     * @throws \NotificationChannels\Twilio\Exceptions\CouldNotSendNotification
     */
    protected function sendMessage($message, $from, $to)
    {
        if ($message instanceof TwilioSmsMessage) {
            return $this->twilio->account->messages->sendMessage(
                $from,
                $to,
                trim($message->content)
            );

            return $response;
        }

        if ($message instanceof TwilioCallMessage) {
            return $this->twilio->account->calls->create(
                $from,
                $to,
                trim($message->url)
            );
        }

        throw CouldNotSendNotification::invalidMessageObject($message);
    }
}
