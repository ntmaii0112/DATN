<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    protected $service;

    public function __construct(ItemService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function show($id)
    {
        try {
            $item = Item::with(['user', 'category', 'images'])->findOrFail($id);

            // Đảm bảo đường dẫn ảnh đúng format
            $images = $item->images->map(function($img) {
                return asset(''.ltrim($img->image_url, '/'));
            })->toArray();

            $user = auth()->user();
            $requestCount = 0;
            $userRequests = collect();
            if ($user) {
                // Lấy tất cả userRequests (không lọc status)
                $userRequests = \App\Models\Transaction::where('receiver_id', $user->id)->get();
                // Đếm các request có status = pending
                $requestCount = $userRequests->where('request_status', 'pending')->count();
                // Lấy danh sách item_id từ các yêu cầu (toàn bộ)
                $requestedItems = $userRequests->pluck('item_id')->toArray();
            }


            return view('items.show', compact('item', 'images','requestCount', 'userRequests'));

        } catch (\Exception $e) {
            Log::error('Error loading item detail', [
                'item_id' => $id,
                'error' => $e->getMessage()
            ]);

            abort(404, 'Item not found');
        }
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:tb_categories,id',
            'item_condition' => 'required|in:new,used',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deleted_image_ids' => 'nullable|string',
            'deposit_amount' => 'required|numeric|min:0',
        ]);
        // Set status to 'submit' before updating
        $validated['status'] = 'submit';
        $item->update($validated);
        // Xử lý ảnh bị xóa (chỉ đánh dấu, không xóa trực tiếp ở đây)
        if ($request->filled('deleted_image_ids')) {
            $this->processDeletedImages($request->deleted_image_ids, $item->id);
        }

        // Xử lý ảnh mới
        if ($request->hasFile('images')) {
            $this->processNewImages($request->file('images'), $item);
        }

        return redirect()->route('items.show', $item->id)
            ->with('success', 'Item updated successfully.');
    }

    protected function processDeletedImages($deletedIdsJson, $itemId)
    {
        $ids = json_decode($deletedIdsJson);
        ItemImage::whereIn('id', $ids)
            ->where('item_id', $itemId)
            ->each(function ($image) {
                if (Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                }
                $image->delete();
            });
    }

    protected function processNewImages($files, $item)
    {
        foreach ($files as $file) {
            $path = $file->store('items', 'public');
            \DB::table('tb_item_images')->insert([
                'item_id' => $item->id,
                'image_url' => 'storage/' . $path,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        }
    }


    public function destroy($id)
    {
        $item = Item::find($id); // Sử dụng find() thay vì findOrFail() để tránh lỗi 404 nếu không tìm thấy item

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        // Kiểm tra quyền sở hữu (nếu cần)
        if (auth()->id() !== $item->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action'
            ], 403);
        }

        // Kiểm tra nếu item đã được lấy và có thể cập nhật
        if ($item) {
            // Cập nhật del_flag thay vì xóa
            $item->del_flag = true;  // Cập nhật trực tiếp trường del_flag
            $item->updated_by = auth()->id();
            $item->updated_at = now();

            if ($item->save()) {
                // Thành công, quay lại trang cũ
                return back()->with('success', 'Item has been deleted successfully');
            } else {
                // Nếu không lưu được, thông báo lỗi
                return back()->with('error', 'Failed to update item.');
            }
        } else {
            return back()->with('error', 'Item not found.');
        }
    }

    public function itemsByCategory($id)
    {
        $category = Category::findOrFail($id);
        $items = Item::where('category_id', $id)->get();

        return view('items.by-category', compact('category', 'items'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $items = Item::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->get();

        return view('items.search-results', compact('items', 'query'));
    }

    public function create()
    {
        try {
            $categories = Category::all();
            Log::info('User opened item create screen.', ['user_id' => Auth::id()]);
            return view('items.create', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Failed to load create screen', ['error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong.');
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:tb_categories,id',
            'item_condition' => 'required|in:new,used',
            'images.*' => 'nullable|image|max:2048', // 2MB mỗi ảnh
            'deposit_amount' => 'required|numeric|min:0',
        ]);

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'item_condition' => $request->item_condition,
            'deposit_amount' => $request->deposit_amount,
            'status' => 'submit',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items', 'public');
                \DB::table('tb_item_images')->insert([
                    'item_id' => $item->id,
                    'image_url' => 'storage/' . $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('items.show', $item->id)->with('success', 'Item created successfully!');
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
