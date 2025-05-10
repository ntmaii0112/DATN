<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            'payment_method' => 'required|in:cash,momo',
        ]);

        $item = Item::findOrFail($request->item_id);

        $transaction = Transaction::create([
            'giver_id' => $item->user_id,
            'receiver_id' => Auth::id(),
            'item_id' => $item->id,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'borrower_name' => $request->borrower_name,
            'contact_info' => $request->contact_info,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'purpose' => $request->purpose,
            'message' => $request->message,
            'request_status' => 'waiting_payment',
            'payment_status' => 'unpaid',
            'payment_method' => $request->payment_method,
        ]);

        if ($request->payment_method === 'momo') {
            return $this->initiateMomoPayment($transaction);
        }

        // For cash payment, send notification immediately
        $transaction->update(['request_status' => 'pending']);
        $emailService = app('email-service');
        $emailService->sendBorrowRequestNotification($transaction);

        return redirect()->back()->with('success', 'Borrow request has been sent successfully!');
    }
    public function initiateMomoPayment($transaction)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        // Sử dụng thông tin xác thực từ tài liệu của MoMo (Test credentials)
        $partnerCode = "MOMOBKUN20180529"; // Mã đối tác
        $accessKey = "klm05TvNBzhg7h7j"; // Khóa truy cập
        $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa"; // Khóa bí mật

        $orderInfo = "Deposit for borrowing item: " . $transaction->item->name;
        $amount = $transaction->item->deposit_amount ? $transaction->item->deposit_amount * 1000 : 10000; // Chuyển đổi sang VND (1000 VND = 1 nghìn đồng)
        $orderId = $transaction->id . "_" . time();
        $redirectUrl = route('momo.return');  // Địa chỉ trả về sau khi thanh toán
        $ipnUrl = route('momo.notify');  // Địa chỉ nhận thông báo IPN
        $requestId = time() . "";
        $extraData = ""; // Dữ liệu bổ sung nếu có

        // Tạo raw hash (chuỗi băm)
        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=captureWallet";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // Dữ liệu gửi đi
        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test Partner",
            'storeId' => "Store001",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => 'captureWallet',
            'signature' => $signature
        ];

        // Gửi yêu cầu đến MoMo
        $response = Http::post($endpoint, $data);

        // Kiểm tra nếu yêu cầu thành công và có URL thanh toán trả về
        if ($response->successful() && isset($response['payUrl'])) {
            return redirect($response['payUrl']);
        } else {
            // Nếu yêu cầu thất bại, ghi log chi tiết lỗi
            // Nếu MoMo trả về lỗi, ghi lại thông tin lỗi chi tiết và thông báo cho người dùng
            Log::error('MoMo payment initiation failed', [
                'response' => $response->json(),
                'status' => $response->status(),
                'transaction_id' => $transaction->id,
                'request_data' => $data,
                'error_message' => $response->json()['message'] // Thêm thông điệp lỗi từ MoMo
            ]);
            // Trả về lỗi cho người dùng với thông điệp rõ ràng hơn
            return redirect()->back()
                ->with('error', 'Could not initiate payment. QR code creation failed. Please try again later.');


            // Trả về lỗi cho người dùng
            return redirect()->back()
                ->with('error', 'Could not initiate payment. Please try again later.');
        }
    }


    /**
     * Gửi email thông báo mượn đồ
     *
     * @param Transaction $transaction
     */
    protected function sendBorrowEmails(Transaction $transaction)
    {
        try {
            // Gửi email cho người cho mượn (chủ đồ)
            Mail::to($transaction->giver->email)
                ->send(new ItemBorrowedNotification($transaction));

            // Gửi email cho người mượn
            if ($transaction->receiver) {
                Mail::to($transaction->receiver->email)
                    ->send(new ItemBorrowedNotification($transaction));
            } else {
                // Nếu người mượn không có tài khoản, gửi đến contact_info
                if (filter_var($transaction->contact_info, FILTER_VALIDATE_EMAIL)) {
                    Mail::to($transaction->contact_info)
                        ->send(new ItemBorrowedNotification($transaction));
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to send borrow emails: ' . $e->getMessage());
            Log::error($e);
        }
    }

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'transactions');

        $user = auth()->user();

        if ($tab === 'requests') {
            $transactions = Transaction::where('receiver_id', $user->id)
                ->whereHas('item', function ($query) {
                    $query->where('del_flag', false);
                })
                ->with(['item', 'giver', 'receiver'])
                ->latest()
                ->paginate(10);
        } elseif ($tab === 'posts') {
            // Add this new section for user's posts
            $items = Item::withoutGlobalScope('not_deleted')
                ->where('user_id', $user->id)
                ->with(['category', 'images'])
                ->latest()
                ->paginate(10);

            return view('transactions.index', [
                'items' => $items,
                'tab' => $tab,
                'showPostsTab' => true
            ]);
        } else {
            $transactions = Transaction::where('giver_id', $user->id)
                ->whereHas('item', function ($query) {
                    $query->where('del_flag', false);
                })
                ->with(['item', 'giver', 'receiver'])
                ->latest()
                ->paginate(10);
        }

        return view('transactions.index', [
            'transactions' => $transactions,
            'tab' => $tab
        ]);
    }

    public function show(Transaction $transaction)
    {
        // Kiểm tra user có quyền xem transaction này không
        if ($transaction->giver_id != auth()->id() && $transaction->receiver_id != auth()->id()) {
            abort(403);
        }
        // Lấy thông tin chi tiết từ các bảng liên quan (Item, Contact Info)
        $item = $transaction->item; // Giả sử rằng mối quan hệ với bảng item đã được định nghĩa trong model Transaction.
        $contactInfo = $transaction->contactInfo; // Giả sử rằng mối quan hệ với bảng contact_info đã được định nghĩa.

        // Lấy thông tin của Giver và Receiver từ mối quan hệ với bảng User
        $giver = $transaction->giver; // Giả sử mối quan hệ đã được định nghĩa trong model Transaction
        $receiver = $transaction->receiver; // Giả sử mối quan hệ đã được định nghĩa trong model Transaction
//        return view('transactions.show', compact('transaction', 'item', 'contactInfo'));
        return view('transactions.show', compact('transaction', 'item', 'contactInfo', 'giver', 'receiver'));

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
            app('email-service')->sendBorrowApprovedNotification($transaction);
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
        // Kiểm tra quyền - chỉ người request mới được cancel
        if ($transaction->receiver_id != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Chỉ cho phép cancel khi ở trạng thái pending
        if ($transaction->request_status != 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending requests can be cancelled.');
        }

        DB::transaction(function () use ($transaction) {
            // Cập nhật trạng thái transaction
            $transaction->update([
                'request_status' => 'cancelled',
                'updated_at' => now(),
            ]);

            // Cập nhật trạng thái item nếu cần
            if ($transaction->item) {
                $transaction->item->update(['status' => 'available']);
            }

            // Gửi thông báo cho người cho mượn
            // $transaction->giver->notify(new RequestCancelled($transaction));
        });

        return redirect()->back()
            ->with('success', 'Request cancelled successfully.');
    }


    public function edit($id)
    {
        try {
            $item = Item::findOrFail($id);
            $categories = Category::all();

            // Authorization check - ensure user owns the item
            if ($item->user_id != auth()->id()) {
                abort(403, 'Unauthorized action.');
            }

            return view('items.edit', compact('item', 'categories'));
        } catch (\Exception $e) {
            Log::error('Error loading edit form', [
                'item_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to load edit form.');
        }
    }
}
