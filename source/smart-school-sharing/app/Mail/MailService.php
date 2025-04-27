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
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mail Service',
        );
    }

    public function build()
    {
//        return $this->subject($this->data['subject'] ?? 'No Subject')
//            ->view('emails.'.$this->template)
//            ->with(['data' => $this->data]);
        return $this->subject($this->data['subject'] ?? 'Thông báo từ ' . config('app.name'))
            ->view('emails.'.$this->template)
            ->with(['data' => $this->data]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.template',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
