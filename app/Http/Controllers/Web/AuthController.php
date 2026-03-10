<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = [
                'email'    => $request->input('login'),
                'password' => $request->input('password'),
            ];

            // If it's not an email, try username
            if (!filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)) {
                $credentials = [
                    'username' => $request->input('login'),
                    'password' => $request->input('password'),
                ];
            }

            if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                
                $admin = Auth::guard('admin')->user();
                if ($admin->status !== 'active') {
                    Auth::guard('admin')->logout();
                    return back()->withErrors(['login' => 'Your account is not active.'])->withInput();
                }

                $admin->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip(),
                ]);

                return redirect()->intended(route('dashboard'));
            }

            return back()->withErrors([
                'login' => 'The provided credentials do not match our records.',
            ])->withInput();

        } catch (Throwable $e) {
            Log::error('Web Login error', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred during login.');
        }
    }

    /**
     * Log the admin out.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
