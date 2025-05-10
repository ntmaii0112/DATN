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

    public function sendItemRejectedNotification($item, $reason)
    {
        try {
            if (!$item->relationLoaded('user')) {
                $item->load('user');
            }

            // Kiểm tra user tồn tại
            if (!$item->user) {
                \Log::error('Cannot send rejection email - no user associated with item', [
                    'item_id' => $item->id
                ]);
                return false;
            }

            Log::info("Preparing to send item rejection email", [
                'to' => $item->user->email,
                'subject' => "Your item '{$item->name}' has been rejected",
                'item_id' => $item->id,
                'reason' => $reason,
                'user' => $item->user,
            ]);

            return $this->send(
                $item->user->email,
                'item_rejected', // view: resources/views/emails/item_rejected.blade.php
                [
                    'subject' => "Your item '{$item->name}' has been rejected",
                    'item' => $item,
                    'user' => $item->user,
                    'reason' => $reason
                ]
            );
        } catch (\Exception $e) {
            Log::error("Send reject email failed: " . $e->getMessage());
            return false;
        }
    }

    public function sendUserReportNotification(Report $report)
    {
        $adminEmail = config('mail.admin_address');

        if (empty($adminEmail)) {
            Log::error('Admin email address is not configured');
            return false;
        }

        try {
            $reporter = $report->reporter;
            $reportedUser = $report->reported;

            if (!$reporter || !$reportedUser) {
                Log::error("Invalid reporter or reported user for report #{$report->id}");
                return false;
            }

            return $this->send(
                $adminEmail,
                'user_reported',
                [
                    'subject'  => "New User Report (#{$report->id})",
                    'report'   => $report,
                    'reporter' => $reporter,
                    'reported' => $reportedUser,
                ]
            );
        } catch (\Exception $e) {
            Log::error("User report email failed for report #{$report->id}: ".$e->getMessage());
            return false;
        }
    }

}
