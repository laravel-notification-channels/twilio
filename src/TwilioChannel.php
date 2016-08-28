<?php

namespace NotificationChannels\Twilio;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use NotificationChannels\Twilio\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Events\NotificationFailed;
use Services_Twilio as Twilio;

class TwilioChannel
{
    /**
     * @var Twilio
     */
    protected $twilio;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * Default 'from' from config.
     * @var string
     */
    protected $from;

    /**
     * TwilioChannel constructor.
     *
     * @param Twilio  $twilio
     * @param Dispatcher  $events
     */
    public function __construct(Twilio $twilio, Dispatcher $events, $from)
    {
        $this->twilio = $twilio;
        $this->events = $events;
        $this->from = $from;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('twilio')) {
            if (! $to = $notifiable->phone_number) {
                return;
            }
        }

        try {
            $message = $notification->toTwilio($notifiable);

            if (is_string($message)) {
                $message = new TwilioSmsMessage($message);
            }

            if (! $message instanceof TwilioMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            return $this->sendMessage($message, $to);
        } catch (Exception $exception) {
            $this->events->fire(
                new NotificationFailed($notifiable, $notification, 'twilio', ['message' => $exception->getMessage()])
            );
        }
    }

    /**
     * Send message to Twilio.
     *
     * @param  TwilioMessage  $message
     * @param  string  $to
     * @return mixed
     *
     * @throws \NotificationChannels\Twilio\Exceptions\CouldNotSendNotification
     */
    protected function sendMessage(TwilioMessage $message, $to)
    {
        $from = $this->getFrom($message);

        if ($message instanceof TwilioSmsMessage) {
            return $this->twilio->account->messages->sendMessage(
                $from,
                $to,
                trim($message->content)
            );
        }

        if ($message instanceof TwilioCallMessage) {
            return $this->twilio->account->calls->create(
                $from,
                $to,
                trim($message->content)
            );
        }

        throw CouldNotSendNotification::invalidMessageObject($message);
    }

    protected function getFrom($message)
    {
        if (! $from = $message->from ?: $this->from) {
            throw CouldNotSendNotification::missingFrom();
        }

        return $from;
    }
}
