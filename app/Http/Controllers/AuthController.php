<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function postRegister(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:8|confirmed',
            'cf-turnstile-response' => app()->environment('production') ? ['required', Rule::turnstile()] : [],
        ]);

        unset($validated['cf-turnstile-response']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => Role::USER,
        ]);

        $randomString = Str::random(5);

        $user->slug = strtolower("{$user->name}{$randomString}{$user->id}");
        $user->save();

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration Success!');
    }

    public function postLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'cf-turnstile-response' => app()->environment('production') ? ['required', Rule::turnstile()] : [],
        ]);

        unset($credentials['cf-turnstile-response']);

        $user = User::where('email', $credentials['email'])->first();

        if ($user->is_banned) {
            return back()->withErrors(['email' => 'Maaf akun anda telah dibanned. Silahkan hubungi admin.']);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Login Success!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout Success!');
    }
}
