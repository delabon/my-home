<?php

declare(strict_types=1);

use App\Actions\Auth\LoginAction;
use App\DTOs\LoginDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

it('logs in a user and regenerate the session', function () {
    Auth::shouldReceive('attempt')
        ->once()
        ->andReturn(true);

    Session::shouldReceive('regenerate')
        ->once()
        ->andReturnNull();

    new LoginAction()->execute(
        new LoginDTO(
            email: 'john@doe.cc',
            password: '12341234'
        )
    );
});
