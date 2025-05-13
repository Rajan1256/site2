<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


Route::get('/cross-login', function (Request $request) {
    try {
        $token = $request->query('token');
        $user = JWTAuth::setToken($token)->authenticate();

        Auth::login($user);

        return redirect('/dashboard');
    } catch (\Exception $e) {
        return redirect('/login')->withErrors(['error' => 'Invalid login token']);
    }
});


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
});
