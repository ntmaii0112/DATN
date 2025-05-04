<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category');

        $featuredItems = Item::with(['user', 'category', 'images'])
            ->whereIn('status', ['available', 'unavailable','pending'])
            ->where('del_flag', false)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->first_image_url = $item->images->isNotEmpty()
                    ? asset('' . ltrim($item->images->first()->image_url, '/'))
                    : null;
                return $item;
            });

        $user = auth()->user();
        $requestedItems = [];
        $requestCount = 0;
        $userRequests = collect();

        if ($user) {
            $userRequests = \App\Models\Transaction::where('receiver_id', $user->id)->get();
            $requestCount = $userRequests->count();
            $requestedItems = $userRequests->pluck('item_id')->toArray();
        }

        $searchResults = null;
        if ($query || $categoryId) {
            $searchResults = Item::with(['user', 'category', 'images'])
                ->whereIn('status', ['available', 'unavailable'])
                ->where('del_flag', false)
                ->when($query, fn($q) => $q->where('name', 'like', "%$query%"))
                ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
                ->latest()
                ->paginate(10)
                ->withQueryString();

            $searchResults->each(function ($item) {
                $item->first_image_url = $item->images->isNotEmpty()
                    ? asset('' . ltrim($item->images->first()->image_url, '/'))
                    : null;
            });
        }

        return view('home', compact('featuredItems', 'searchResults', 'requestCount', 'userRequests'));
    }


    public function search(Request $request)
    {
        $query = $request->input('query');
        $user = auth()->user();
        // Lấy danh sách ID các item mà user hiện tại đã gửi yêu cầu mượn
        $userRequests = $user
            ? Transaction::where('receiver_id', $user->id)->get()
            : collect(); // Trả về empty collection nếu chưa login

        $requestCount = $userRequests->count(); // Đếm trên Collection
        $requestedItemIds = $userRequests->pluck('item_id')->toArray();


        // Tìm kiếm items
        $searchResults = Item::with(['user', 'category', 'images'])
            ->whereIn('status', ['available', 'unavailable','pending'])
            ->where('del_flag', false)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%");
            })
            ->latest()
            ->paginate(10);

        // Xử lý hình ảnh và thêm trạng thái yêu cầu mượn
        $searchResults->getCollection()->transform(function ($item) use ($requestedItemIds) {
            // Xử lý hình ảnh
            $item->first_image_url = $item->images->isNotEmpty()
                ? asset(ltrim($item->images->first()->image_url, '/'))
                : null;

            // Thêm trạng thái đã yêu cầu mượn hay chưa
            $item->already_requested = in_array($item->id, $requestedItemIds);

            return $item;
        });
        $requestLimit = (int) env('MAX_BORROW_REQUESTS', 10); // fallback mặc định là 10


        // 🔥 Lấy featured items nếu không có query
        $featuredItems = collect();
        if (!$query) {
            $featuredItems = Item::with(['images'])
                ->whereIn('status', ['available', 'unavailable','pending'])
                ->where('del_flag', false)
                ->latest()
                ->take(6)
                ->get();

            $featuredItems->transform(function ($item) {
                $item->first_image_url = $item->images->isNotEmpty()
                    ? asset(ltrim($item->images->first()->image_url, '/'))
                    : null;
                return $item;
            });
        }


        return view('home', [
            'searchResults' => $searchResults,
            'requestedItemIds' => $requestedItemIds,
            'requestCount' => $requestCount,
            'userRequests' => $userRequests,
            'requestLimit' => $requestLimit,
            'query' => $query,
            'featuredItems' => $featuredItems
        ]);
    }

    protected function getFeaturedItems()
    {
        return Item::with(['user', 'category', 'images'])
//            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($item) {
                $item->first_image_url = $item->images->isNotEmpty()
                    ? asset(ltrim($item->images->first()->image_url, '/'))
                    : null;
                return $item;
            });
    }

    // HomeController.php

    public function loadFeaturedItems(Request $request)
    {
        $featuredItems = Item::with(['images'])
            ->where('status', 'available','pending')
            ->latest()
            ->paginate(4); // 4 item mỗi lần

        $featuredItems->getCollection()->transform(function ($item) {
            $item->first_image_url = $item->images->isNotEmpty()
                ? asset(ltrim($item->images->first()->image_url, '/'))
                : null;
            return $item;
        });

        return response()->json($featuredItems);
    }


}
