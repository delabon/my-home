<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class LoginController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // TODO: move this validation to a dedicated request class
        $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ]);

        $succeeded = Auth::attempt($request->all());

        if (! $succeeded) {
            return to_route('login')->withErrors([
                'email' => 'Failed to login, please check your email and/or password.'
            ]);
        }

        $request->session()->regenerate();

        return to_route('dashboard');
    }
}
