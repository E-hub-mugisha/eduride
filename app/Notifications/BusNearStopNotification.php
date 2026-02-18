<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BusNearStopNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $trip;
    protected $stop;

    public function __construct($trip, $stop)
    {
        $this->trip = $trip;
        $this->stop = $stop;
    }

    public function via($notifiable)
    {
        return ['database']; // later add 'mail','nexmo'
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Bus Near Stop',
            'message' => 'Bus is approaching ' . $this->stop->name,
            'trip_id' => $this->trip->id
        ];
    }
}
