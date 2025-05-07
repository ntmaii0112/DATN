<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

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

    protected function sendBorrowEmails(Transaction $transaction)
    {
        $item = $transaction->item;
        $giver = $transaction->giver;
        $receiver = $transaction->receiver;

        // Dữ liệu email chung
        $emailData = [
            'subject' => 'Item Borrowing Request Notification: ' . $item->name,
            'transaction' => $transaction,
            'item' => $item,
            'giver' => $giver,
            'receiver' => $receiver,
            'borrower_name' => $transaction->borrower_name,
        ];

        try {
            // Gửi cho người cho mượn
            Mail::to($giver->email)
                ->send(new MailService($emailData, 'item_borrowed'));

            // Gửi cho người mượn
            $receiverEmail = $receiver ? $receiver->email : $transaction->contact_info;
            if (filter_var($receiverEmail, FILTER_VALIDATE_EMAIL)) {
                Mail::to($receiverEmail)
                    ->send(new MailService($emailData, 'item_borrowed'));
            }

        } catch (\Exception $e) {
            Log::error('Gửi email thất bại: ' . $e->getMessage());
        }
    }
}
