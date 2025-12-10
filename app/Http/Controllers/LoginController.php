<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Auth\LogoutAction;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function destroy(LogoutAction $action): RedirectResponse
    {
        $action->execute();

        return to_route('login');
    }
}
