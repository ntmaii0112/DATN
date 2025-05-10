<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function show($id)
    {
        $user = User::with(['items.images'])->findOrFail($id);

        // Gắn first_image_url vào mỗi item (nếu có image)
        $user->items->each(function ($item) {
            $item->first_image_url = $item->images->isNotEmpty()
                ? asset('' . ltrim($item->images->first()->image_url, '/'))
                : null;
        });
        return view('users.show', compact('user'));
    }


    public function store(Request $request)
    {
        $data = $request->all();
        return response()->json($this->service->create($data));
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
}
