<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService {
    public function send($to, $template, $data = [])
    {
        try {
            Mail::send('emails.' . $template, ['data' => $data], function ($message) use ($to, $data) {
                $message->to($to)
                    ->subject($data['subject'] ?? 'Thông báo từ hệ thống');
            });
            return true;
        } catch (\Exception $e) {
            Log::error("Email sending failed: " . $e->getMessage());
            return false;
        }
    }

    public function sendBorrowRequestNotification($transaction)
    {
        try {
            $item = $transaction->item;
            $giver = $transaction->giver;
            $receiver = $transaction->receiver;
            Log::info("Preparing to send borrow request email", [
                'to' => $giver->email,
                'subject' => "New Borrow Request for: " . $item->name,
                'item_name' => $item->name,
                'giver_name' => $giver->name,
                'receiver_name' => $receiver->name,
                'transaction_id' => $transaction->id
            ]);
            return $this->send(
                $giver->email,
                'item_borrowed',
                [
                    'subject' => "New Borrow Request for: " . $item->name,
                    'item' => $item,
                    'giver' => $giver,
                    'receiver' => $receiver,
                    'transaction' => $transaction
                ]
            );
        } catch (\Exception $e) {
            Log::error("Borrow request email failed: " . $e->getMessage());
            return false;
        }
    }

    public function sendBorrowApprovedNotification($transaction)
    {
        try {
            $item = $transaction->item;
            $giver = $transaction->giver;
            $receiver = $transaction->receiver;

            // Log nội dung email gửi khi approve
            Log::info("Sending borrow approval email", [
                'to' => $receiver->email,
                'subject' => "Your borrow request for '{$item->name}' has been approved",
                'item_name' => $item->name,
                'giver_name' => $giver->name,
                'receiver_name' => $receiver->name,
                'transaction_id' => $transaction->id
            ]);

            return $this->send(
                $receiver->email,
                'borrow_approved',
                [
                    'subject' => "Your borrow request for '{$item->name}' has been approved",
                    'item' => $item,
                    'giver' => $giver,
                    'receiver' => $receiver,
                    'transaction' => $transaction
                ]
            );
        } catch (\Exception $e) {
            Log::error("Borrow approval email failed: " . $e->getMessage());
            return false;
        }
    }

}
