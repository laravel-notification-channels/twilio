<?php

namespace NotificationChannels\Twilio;

use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\Exceptions\CouldNotSendNotification;

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
     * TwilioChannel constructor.
     *
     * @param Twilio $twilio
     * @param Dispatcher $events
     */
    public function __construct(Twilio $twilio, Dispatcher $events)
    {
        $this->twilio = $twilio;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return mixed
     * @throws Exception
     */
    public function send($notifiable, $notification)
    {
        try {
            $message = $notification->toTwilio($notifiable);

            if (is_string($message)) {
                $message = new TwilioSmsMessage($message);
            }

            if (! $message instanceof TwilioMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }
            
            $to = $this->getTo($notifiable, $notification, $message);
            $useSender = $this->canReceiveAlphanumericSender($notifiable);

            return $this->twilio->sendMessage($message, $to, $useSender);
        } catch (Exception $exception) {
            $event = new NotificationFailed(
                $notifiable,
                $notification,
                'twilio',
                ['message' => $exception->getMessage(), 'exception' => $exception]
            );

            $this->events->dispatch($event);

            if ($this->twilio->config->isIgnoredErrorCode($exception->getCode())) {
                return;
            }

            throw $exception;
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @param TwilioMessage $message
     *
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function getTo($notifiable, $notification, $message)
    {
        if ($message->getTo()) {
            return $message->getTo();
        }
        if ($notifiable->routeNotificationFor(self::class, $notification)) {
            return $notifiable->routeNotificationFor(self::class, $notification);
        }
        if ($notifiable->routeNotificationFor('twilio', $notification)) {
            return $notifiable->routeNotificationFor('twilio', $notification);
        }
        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }

        throw CouldNotSendNotification::invalidReceiver();
    }

    /**
     * Get the alphanumeric sender.
     *
     * @param $notifiable
     *
     * @return mixed|null
     * @throws CouldNotSendNotification
     */
    protected function canReceiveAlphanumericSender($notifiable)
    {
        return method_exists($notifiable, 'canReceiveAlphanumericSender') &&
            $notifiable->canReceiveAlphanumericSender();
    }
}
