<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TripStartedMail extends Mailable
{
    public $trip;
    public $child;

    public function __construct($trip, $child)
    {
        $this->trip = $trip;
        $this->child = $child;
    }

    public function build()
    {
        return $this->subject('Trip Started')
            ->view('emails.trip_started');
    }
}
