<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BusNearStopMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $trip;
    public $stop;
    public $eta;
    public $child;

    /**
     * Create a new message instance.
     */
    public function __construct($trip, $stop, $eta, $child = null)
    {
        $this->trip = $trip;
        $this->stop = $stop;
        $this->eta = $eta;
        $this->child = $child;
    }

    /**
     * Email subject
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚌 Bus Approaching ' . $this->stop->name,
        );
    }

    /**
     * Email view
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bus_near_stop',
            with: [
                'trip'  => $this->trip,
                'stop'  => $this->stop,
                'eta'   => $this->eta,
                'child' => $this->child,
            ],
        );
    }

    /**
     * Attachments (optional)
     */
    public function attachments(): array
    {
        return [];
    }
}
