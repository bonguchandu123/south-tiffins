<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_id')) {
            return redirect()->route('admin.dashboard');
        }
        return view('login');
    }
public function login(Request $request)
{
    $email    = trim($request->input('email'));
    $password = trim($request->input('password'));

    if (!$email || !$password) {
        return back()->with('error', 'Email and password are required.');
    }

    $admin = DB::table('admins')->where('email', $email)->first();

    if (!$admin || !password_verify($password, $admin->password)) {
        return back()->with('error', 'Invalid email or password.');
    }

    $request->session()->put('admin_id',    $admin->id);
    $request->session()->put('admin_name',  $admin->name);
    $request->session()->put('admin_email', $admin->email);
    $request->session()->save();

    \Log::info('Admin logged in', [
        'admin_id'   => session('admin_id'),
        'session_id' => session()->getId()
    ]);

    return redirect()->route('admin.dashboard');
}

    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}