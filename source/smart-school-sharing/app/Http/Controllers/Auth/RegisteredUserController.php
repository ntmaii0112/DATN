<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        event(new Registered($user));

        $this->sendWelcomeEmail($user);
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    protected function sendWelcomeEmail($user)
    {
        try {
            app('email-service')->send(
                $user->email,
                'welcome',
                [
                    'subject' => 'Welcome to ' . config('app.name'),
                    'user' => $user,
                    'login_url' => route('login'),
                    'app_name' => config('app.name')
                ]
            );
        } catch (\Exception $e) {
            \Log::error("Failed to send welcome email: " . $e->getMessage());
        }
    }
}
