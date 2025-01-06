<?php

namespace App\Mail;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewSubscribedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly string $reset_url, public readonly Plan $plan)
        {
        }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Subscribed',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-subscribed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
