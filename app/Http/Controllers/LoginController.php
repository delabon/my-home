<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class LoginController extends Controller
{
    public function store(LoginRequest $request): RedirectResponse
    {
        $succeeded = Auth::attempt($request->validated());

        if (! $succeeded) {
            return to_route('login')->withErrors([
                'email' => 'Failed to login, please check your email and/or password.'
            ]);
        }

        $request->session()->regenerate();

        return to_route('dashboard');
    }
}
