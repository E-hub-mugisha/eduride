<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TripDelayedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $trip;
    public $reason;

    public function __construct($trip, $reason = null)
    {
        $this->trip = $trip;
        $this->reason = $reason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠ Trip Delayed - ' . $this->trip->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trip_delayed',
            with: [
                'trip'   => $this->trip,
                'reason' => $this->reason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
