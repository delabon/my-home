<?php

declare(strict_types=1);

use App\Http\Controllers\LoginController;
use App\Http\Requests\LoginRequest;
use Database\Factories\UserFactory;
use Illuminate\Http\RedirectResponse;

it('logs-in a user successfully', function () {
    $password = '12345678';
    $user = UserFactory::new()->create([
        'password' => $password,
    ]);
    $sessionStore = app('session.store');
    $request = LoginRequest::create('/login', 'POST', [
        'email' => $user->email,
        'password' => $password,
    ]);
    $request->setLaravelSession($sessionStore);
    $controller = new LoginController();

    $oldSessionId = session()->id();

    $response = $controller->store($request);

    $newSessionId = session()->id();

    $this->assertAuthenticated();
    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->isRedirect(route('dashboard')))->toBeTrue()
        ->and(auth()->user()->id)->toBe($user->id)
        ->and($oldSessionId)->not()->toBe($newSessionId);
});

it('fails to log-in a user when email does not exist', function () {
    $password = '12345678';
    $user = UserFactory::new()->create([
        'password' => $password,
    ]);
    $sessionStore = app('session.store');
    $request = LoginRequest::create('/login', 'POST', [
        'email' => $user->email,
        'password' => 'ksjdi93219',
    ]);
    $request->setLaravelSession($sessionStore);
    $controller = new LoginController();

    $oldSessionId = session()->id();

    $response = $controller->store($request);

    $newSessionId = session()->id();

    $this->assertGuest();
    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->isRedirect(route('login')))->toBeTrue()
        ->and($oldSessionId)->toBe($newSessionId);
});

it('fails to log-in a user when password is invalid', function () {
    $sessionStore = app('session.store');
    $request = LoginRequest::create('/login', 'POST', [
        'email' => 'does-not-exist@test.cc',
        'password' => '12341234',
    ]);
    $request->setLaravelSession($sessionStore);
    $controller = new LoginController();

    $oldSessionId = session()->id();

    $response = $controller->store($request);

    $newSessionId = session()->id();

    $this->assertGuest();
    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->isRedirect(route('login')))->toBeTrue()
        ->and($oldSessionId)->toBe($newSessionId);
});
