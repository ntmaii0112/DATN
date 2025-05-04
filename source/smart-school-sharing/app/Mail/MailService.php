<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailService extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $template;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $template)
    {
        $this->data = $data;
        $this->template = $template;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->data['subject'] ?? 'Thông báo từ ' . config('app.name'))
            ->view('emails.'.$this->template)
            ->with(['data' => $this->data]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->data['subject'] ?? 'Thông báo từ ' . config('app.name');
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.'.$this->template,
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
