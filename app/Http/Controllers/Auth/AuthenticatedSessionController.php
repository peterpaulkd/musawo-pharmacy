<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     //specify who sees what
    //     $user = Auth::user();
    //     if ($user && $user->role === 'admin') {
    //        return redirect()->route('admin.dashboard');
    //     } elseif ($user && $user->role === 'nurse') {
    //        return redirect()->route('nurse.dashboard');
    //     } else {
    //        return redirect('/'); // fallback
    //     }

    //    // return redirect()->intended(route('dashboard', absolute: false));
    // }


    


    
//      public function store(Request $request): RedirectResponse
// {
//     // Validate input
//     $request->validate([
//         'role' => 'required|string',
//         'password' => 'required|string',
//     ]);

//     // Get the user for this role
//     $user = User::where('role', $request->role)->first();

//     if (!$user) {
//         return back()->withErrors(['role' => 'Role not found.']);
//     }

//     if (!Hash::check($request->password, $user->password)) {
//         return back()->withErrors(['password' => 'Password does not match.']);
//     }

//     // Login and regenerate session
//     Auth::login($user);
//     $request->session()->regenerate();

//     // Redirect by role
//     return $user->role === 'admin'
//         ? redirect()->route('admin.dashboard')
//         : redirect()->route('nurse.dashboard');
// }

public function store(Request $request): RedirectResponse
{
    // Validate input
    $request->validate([
        'role' => 'required|string',
        'password' => 'required|string',
    ]);

    // Get the user for this role
    $user = User::where('role', $request->role)->first();

    // Check if user exists
    if (!$user) {
        return back()->withErrors(['role' => 'Role not found.']);
    }

    // Verify password
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Password does not match.']);
    }

    // Login the user
    Auth::login($user);

    // Regenerate session
    $request->session()->regenerate();

    // Redirect by role
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'nurse') {
        return redirect()->route('nurse.dashboard');
    }

    // Fallback
    return redirect('/');
}



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}