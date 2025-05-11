<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        try {
            // Lưu vào DB
            $contact = Contact::create($data);

            // Gửi email thông báo cho admin
            Log::info("Attempting to send contact notification email for contact #{$contact->id}");
            $emailService = app('email-service');
            $emailSent = $emailService->sendContactNotification($contact);

            if ($emailSent) {
                Log::info("Contact notification email successfully sent for contact #{$contact->id}");
            } else {
                Log::warning("Failed to send contact notification email for contact #{$contact->id}");
            }
            return back()->with('success', 'Thank you for contacting us!');
        } catch (\Exception $e) {
            Log::error("Contact form submission failed: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with('error', 'An error occurred while submitting your message.');
        }
    }
}
