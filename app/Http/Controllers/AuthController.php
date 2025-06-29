<?php
namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
//  Hannah
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.admin');
            } elseif ($user->role === 'warehouse') {
                return redirect()->route('dashboard.warehouse');
            } elseif ($user->role === 'delivery') {
                return redirect()->route('dashboard.delivery');
            }
        }
        
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}