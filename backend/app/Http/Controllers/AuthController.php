<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegisterBusiness()
    {
        return view('auth.register-business');
    }

    public function showRegisterCustomer()
    {
        return view('auth.register-customer');
    }

    public function registerBusiness(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $role = Role::firstOrCreate(['name' => 'business_owner'], ['label' => 'Business Owner']);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role_id' => $role->id,
            'status' => 'active',
        ]);

        // Auto-initialize associated business record
        Business::create([
            'user_id' => $user->id,
            'name' => $user->name . " Enterprise",
            'owner_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'status' => 'pending',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('business.dashboard')->with('success', 'Business account created! Please complete your business profile.');
    }

    public function registerCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $role = Role::firstOrCreate(['name' => 'customer'], ['label' => 'Customer']);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role_id' => $role->id,
            'status' => 'active',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard')->with('success', 'Welcome to Business Connect! Your account has been created.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Check if user exists and verify account status
        $user = User::where('email', $credentials['email'])->first();
        if ($user && $user->status !== 'active') {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Your account is currently ' . $user->status . '. Please contact support.']);
        }

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $authUser = Auth::user();

            if ($authUser->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($authUser->isBusinessOwner()) {
                return redirect()->intended(route('business.dashboard'));
            } else {
                return redirect()->intended(route('customer.dashboard'));
            }
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Successfully logged out.');
    }
}
