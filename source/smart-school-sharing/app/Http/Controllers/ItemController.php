<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

            return view('items.show', compact('item', 'images'));

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
        $data = $request->all();
        return response()->json($this->service->update($id, $data));
    }

    public function destroy($id)
    {
        return response()->json($this->service->delete($id));
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
            'status' => 'required|in:available,unavailable',
            'images.*' => 'nullable|image|max:2048', // 2MB mỗi ảnh
        ]);

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'item_condition' => $request->item_condition,
            'status' => $request->status,
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

}
