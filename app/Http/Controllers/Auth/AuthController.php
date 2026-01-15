<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CartController;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect($this->redirectForRole(Auth::user()));
        }

        return view('customer.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($validated, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            CartController::loadCartFromDatabase($user->id);

            return redirect()->intended($this->redirectForRole($user))->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('customer.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('customer');
        event(new Registered($user));
        Auth::login($user);

        CartController::loadCartFromDatabase($user->id);

        return redirect($this->redirectForRole($user))->with('success', 'Account created and logged in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully.');
    }

    private function redirectForRole(User $user): string
    {
        return match (true) {
            $user->hasRole('admin') => '/test-admin',
            $user->hasRole('staff') => '/test-staff',
            default => '/',
        };
    }
}
