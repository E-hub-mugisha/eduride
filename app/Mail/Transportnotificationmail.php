<?php

namespace App\Mail;

use App\Models\TransportNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransportNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly TransportNotification $notification
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->notification->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transport-notification',
            with: [
                'notification' => $this->notification,
                'trip'         => $this->notification->trip,
                'user'         => $this->notification->user,
                'meta'         => $this->notification->meta ?? [],
            ],
        );
    }
}