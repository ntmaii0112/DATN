<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin-access');
    }

    public function index()
    {
        $users = User::query()
            ->where('role', 'user') // Giữ chặt điều kiện này
            ->when(request('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%')
                        ->orWhere('email', 'like', '%' . request('search') . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.accounts.index', compact('users'));
    }

    public function toggle(User $user)
    {
        $this->authorize('update', $user);

        $user->update(['status' => !$user->status]);

        return back()->with('success',
            "Đã " . ($user->status ? 'kích hoạt' : 'vô hiệu hóa') . " tài khoản {$user->name}"
        );
    }
    public function destroy(User $user)
    {
        try {
            $this->authorize('delete', $user);
            $user->delete();
            return redirect()->back()->with('success', "Đã xóa tài khoản {$user->name}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xóa thất bại: ' . $e->getMessage());
        }
    }

}
