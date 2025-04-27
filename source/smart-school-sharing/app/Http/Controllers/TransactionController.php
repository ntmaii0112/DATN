<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function storeBorrowRequest(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:tb_items,id',
            'borrower_name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'purpose' => 'required|string|max:255',
            'message' => 'nullable|string',
            'agreement' => 'required|accepted',
        ]);

        $item = Item::findOrFail($request->item_id);

        // Tạo transaction mới
        $transaction = Transaction::create([
            'giver_id' => $item->user_id, // Người cho mượn
            'receiver_id' => Auth::id(), // Người mượn (người đăng nhập)
            'item_id' => $item->id,
            'status' => 'pending', // Trạng thái giao dịch
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'borrower_name' => $request->borrower_name,
            'contact_info' => $request->contact_info,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'purpose' => $request->purpose,
            'message' => $request->message,
            'request_status' => 'pending', // Trạng thái yêu cầu
        ]);

        // Cập nhật trạng thái item nếu cần
        $item->update(['status' => 'pending']);

        // Gửi thông báo cho người cho mượn
//        $item->user->notify(new NewBorrowRequest($transaction));
        return redirect()->back()->with('success', 'Yêu cầu mượn đã được gửi thành công!');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $transactions = Transaction::query()
            ->with(['item', 'giver', 'receiver', 'item.category'])
            ->when($request->tab === 'requests', function ($query) use ($user) {
                // Chỉ lấy các request mà user hiện tại là người yêu cầu
                return $query->where('receiver_id', $user->id);
            }, function ($query) use ($user) {
                // Mặc định lấy tất cả transactions liên quan đến user
                return $query->where(function($q) use ($user) {
                    $q->where('giver_id', $user->id)
                        ->orWhere('receiver_id', $user->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        // Kiểm tra user có quyền xem transaction này không
        if ($transaction->giver_id != auth()->id() && $transaction->receiver_id != auth()->id()) {
            abort(403);
        }
        return view('transactions.show', compact('transaction'));
    }

    public function approve(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'request_status' => 'approved',
                'updated_at' => now(),
            ]);

            // Update item status if needed
            $transaction->item->update([
                'status' => 'borrowed'
            ]);

            // Send notification to receiver
//            NotificationService::send(
//                $transaction->receiver,
//                'transaction_approved',
//                "Your request for '{$transaction->item->name}' has been approved!",
//                $transaction
//            );
        });

        return redirect()->back()->with('success', 'Transaction approved successfully!');
    }

    public function reject(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'rejection_reason' => 'sometimes|string|max:255'
        ]);

        DB::transaction(function () use ($transaction, $validated) {
            $transaction->update([
                'request_status' => 'rejected',
                'updated_at' => now(),
                'message' => $validated['rejection_reason'] ?? null,
            ]);

            // Update item status if needed
            $transaction->item->update([
                'status' => 'available'
            ]);

            // Send notification to receiver
//            NotificationService::send(
//                $transaction->receiver,
//                'transaction_rejected',
//                "Your request for '{$transaction->item->name}' has been rejected." .
//                ($validated['rejection_reason'] ? " Reason: {$validated['rejection_reason']}" : ''),
//                $transaction
//            );
        });

        return redirect()->back()->with('success', 'Transaction rejected successfully!');
    }

    public function cancel(Transaction $transaction)
    {
        // Authorization check (example using policies)
        $this->authorize('cancel', $transaction);

        // Business logic to cancel transaction
        $transaction->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction cancelled successfully');
    }
}
