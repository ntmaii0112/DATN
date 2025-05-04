<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminItemController extends Controller
{
    public function index()
    {
        $items = Item::withoutGlobalScope('not_deleted') // <- Bỏ qua scope del_flag
        ->with('user', 'category')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.items.index', compact('items'));
    }

    public function approve(Item $item)
    {
        $item->update([
            'status' => 'available',
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Item approved!');
    }

    public function destroy(Item $item)
    {
        try {
            $item->del_flag = true;
            $item->save();
            return redirect()->route('admin.items.index')->with('success', 'Đã xóa item thành công.');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa item', ['error' => $e->getMessage(), 'item_id' => $item->id]);
            return back()->with('error', 'Xóa item thất bại.');
        }
    }


}
