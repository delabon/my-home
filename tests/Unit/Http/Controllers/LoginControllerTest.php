<?php

declare(strict_types=1);

use App\Actions\Auth\LoginAction;
use App\Http\Controllers\LoginController;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Tests\NewUser;

it('logs-in a user successfully', function () {
    $user = new NewUser()->user;
    $sessionStore = app('session.store');
    $request = LoginRequest::create('/login', 'POST', [
        'email' => NewUser::VALID_EMAIL,
        'password' => NewUser::VALID_PASSWORD,
    ]);
    $validator = validator($request->all(), $request->rules());
    $request->setValidator($validator);
    $request->setLaravelSession($sessionStore);
    $controller = new LoginController();

    $oldSessionId = session()->id();

    $response = $controller->store($request, new LoginAction());

    $newSessionId = session()->id();

    $this->assertAuthenticated();
    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->isRedirect(route('dashboard')))->toBeTrue()
        ->and(auth()->user()->id)->toBe($user->id)
        ->and($oldSessionId)->not()->toBe($newSessionId);
});

it('fails to log-in a user when email does not exist', function () {
    $sessionStore = app('session.store');
    $request = LoginRequest::create('/login', 'POST', [
        'email' => NewUser::NON_EXISTENT_EMAIL,
        'password' => NewUser::VALID_PASSWORD,
    ]);
    $validator = validator($request->all(), $request->rules());
    $request->setValidator($validator);
    $request->setLaravelSession($sessionStore);
    $controller = new LoginController();

    $oldSessionId = session()->id();

    $response = $controller->store($request, new LoginAction());

    $newSessionId = session()->id();

    $this->assertGuest();
    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->isRedirect(route('login')))->toBeTrue()
        ->and($oldSessionId)->toBe($newSessionId);
});

it('fails to log-in a user when password is invalid', function () {
    new NewUser();
    $sessionStore = app('session.store');
    $request = LoginRequest::create('/login', 'POST', [
        'email' => NewUser::VALID_EMAIL,
        'password' => NewUser::INVALID_PASSWORD,
    ]);
    $validator = validator($request->all(), $request->rules());
    $request->setValidator($validator);
    $request->setLaravelSession($sessionStore);
    $controller = new LoginController();

    $oldSessionId = session()->id();

    $response = $controller->store($request, new LoginAction());

    $newSessionId = session()->id();

    $this->assertGuest();
    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->isRedirect(route('login')))->toBeTrue()
        ->and($oldSessionId)->toBe($newSessionId);
});
