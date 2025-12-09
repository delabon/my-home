<?php

declare(strict_types=1);

use App\Http\Controllers\LoginController;
use App\Http\Requests\LoginRequest;
use Database\Factories\UserFactory;
use Illuminate\Http\RedirectResponse;

const TEST_VALID_PASSWORD = '12345678';
const TEST_INVALID_PASSWORD = 'wrong-password';
const TEST_NON_EXISTENT_EMAIL = 'non-existent@test.cc';
const TEST_VALID_EMAIL = 'john@doe.cc';

it('logs-in a user successfully', function () {
    $user = UserFactory::new()->create([
        'email' => TEST_VALID_EMAIL,
        'password' => TEST_VALID_PASSWORD,
    ]);
    $sessionStore = app('session.store');
    $request = LoginRequest::create('/login', 'POST', [
        'email' => TEST_VALID_EMAIL,
        'password' => TEST_VALID_PASSWORD,
    ]);
    $validator = validator($request->all(), $request->rules());
    $request->setValidator($validator);
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
    $sessionStore = app('session.store');
    $request = LoginRequest::create('/login', 'POST', [
        'email' => TEST_NON_EXISTENT_EMAIL,
        'password' => TEST_VALID_PASSWORD,
    ]);
    $validator = validator($request->all(), $request->rules());
    $request->setValidator($validator);
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
    $user = UserFactory::new()->create([
        'email' => TEST_VALID_EMAIL,
        'password' => TEST_VALID_PASSWORD,
    ]);
    $sessionStore = app('session.store');
    $request = LoginRequest::create('/login', 'POST', [
        'email' => TEST_VALID_EMAIL,
        'password' => TEST_INVALID_PASSWORD,
    ]);
    $validator = validator($request->all(), $request->rules());
    $request->setValidator($validator);
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
