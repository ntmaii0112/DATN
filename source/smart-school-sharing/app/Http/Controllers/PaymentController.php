<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function handleReturn(Request $request)
    {
        $orderId = $request->input('orderId');
        $resultCode = $request->input('resultCode');

        // Extract transaction ID from orderId (format: transactionId_timestamp)
        $transactionId = explode('_', $orderId)[0];
        $transaction = Transaction::findOrFail($transactionId);

        if ($resultCode == 0) {
            // Payment successful
            $transaction->update([
                'payment_status' => 'paid',
                'request_status' => 'pending' // Waiting for owner approval
            ]);

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Payment completed successfully! Your request is now pending approval.');
        } else {
            // Payment failed
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Payment failed. Please try again.');
        }
    }
    public function handleNotify(Request $request)
    {
        $data = $request->all();
        Log::info('Momo notify: ', $data);

        if ($data['resultCode'] == 0) {
            $orderId = explode('_', $data['orderId'])[0];
            $transaction = Transaction::find($orderId);
            if ($transaction) {
                $transaction->payment_status = 'paid';
                $transaction->request_status = 'pending';
                $transaction->save();
                // Gửi email thông báo
                $emailService = app('email-service');
                $emailService->sendBorrowRequestNotification($transaction);
            }
        }
        return response()->json(['message' => 'OK']);
    }
}
