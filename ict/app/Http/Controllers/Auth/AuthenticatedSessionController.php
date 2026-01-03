<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        $remember = $request->filled('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // Prefer explicit helper methods if they exist; otherwise fall back to a 'role' column.
        $role = null;
        if (method_exists($user, 'isAdminIct') && $user->isAdminIct()) {
            $role = 'admin_ict';
        } elseif (method_exists($user, 'isAdminHr') && $user->isAdminHr()) {
            $role = 'admin_hr';
        } elseif (method_exists($user, 'isStaff') && $user->isStaff()) {
            $role = 'staff';
        } elseif (property_exists($user, 'role') || isset($user->role)) {
            $role = $user->role;
        }

        // Redirect according to role (use named routes)
        if ($role === 'admin_ict' || $role === 'admin-ict') {
            return redirect()->intended(route('dashboard.admin'));
        }

        if ($role === 'admin_hr' || $role === 'admin-hr') {
            return redirect()->intended(route('dashboard.hr'));
        }

        // fallback to staff/dashboard
        return redirect()->intended(route('dashboard.staff'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
