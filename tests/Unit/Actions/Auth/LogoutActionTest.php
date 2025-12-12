<?php

declare(strict_types=1);

use App\Actions\Auth\LogoutAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

it('logs out a user and regenerate the session', function () {
    Auth::shouldReceive('logout')
        ->once()
        ->andReturnNull();

    Session::shouldReceive('invalidate')
        ->once()
        ->andReturnNull();

    Session::shouldReceive('regenerateToken')
        ->once()
        ->andReturnNull();

    new LogoutAction()->execute();
});
