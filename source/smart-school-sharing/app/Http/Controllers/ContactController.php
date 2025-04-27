<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // Xử lý dữ liệu từ form liên hệ
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        Contact::create($data);
        // Ví dụ: Gửi email, lưu database hoặc flash message
        return back()->with('success', 'Cảm ơn bạn đã liên hệ!');
    }
}
