<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia;
use Tests\NewUser;

it('renders the login page', function () {
    $response = $this->get(route('login'));

    $response->assertOk()
        ->assertInertia(fn (AssertableInertia $component) => $component
            ->component('auth/Login')
        );
});

it('signs in successfully', function () {
    $user = new NewUser()->user;

    $oldSessionId = session()->id();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => NewUser::VALID_PASSWORD,
    ]);

    $newSessionId = session()->id();

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id)
        ->and($oldSessionId)->not->toBe($newSessionId);
});

it('signs in a different user successfully', function () {
    $user = new NewUser([
        'email' => 'mike@jordon.com',
    ])->user;

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => NewUser::VALID_PASSWORD,
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id);
});

test('trying to sign-in while already signed-in returns a redirect response to dashboard page', function () {
    $user = new NewUser([
        'email' => 'mike@jordon.com',
    ])->user;

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => NewUser::VALID_PASSWORD,
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id);

    $anotherUser = new NewUser([
        'email' => 'lina@doe.com',
    ])->user;

    $newResponse = $this->post(route('login.store'), [
        'email' => $anotherUser->email,
        'password' => NewUser::VALID_PASSWORD,
    ]);

    $newResponse->assertRedirectToRoute('dashboard');
    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id);
});

dataset('invalid_email_data', [
    [
        '',
        'The email field is required.',
    ],
    [
        'john.doe',
        'The email field must be a valid email address.',
    ],
    [
        '@',
        'The email field must be a valid email address.',
    ],
    [
        '@test.com',
        'The email field must be a valid email address.',
    ],
]);

it('fails with invalid email addresses', function (string $invalidEmail, string $expectedMessage) {
    new NewUser();

    $response = $this->post(route('login.store'), [
        'email' => $invalidEmail,
        'password' => NewUser::VALID_PASSWORD,
    ]);

    $response->assertRedirectBack();
    $response->assertSessionHasErrors([
        'email' => $expectedMessage,
    ]);
    $this->assertGuest();
})->with('invalid_email_data');

dataset('invalid_password_data', [
    [
        '',
        'The password field is required.',
    ],
    [
        true,
        'The password field must be at least 8 characters.',
    ],
    [
        '123',
        'The password field must be at least 8 characters.',
    ],
]);

it('fails with invalid passwords', function (mixed $invalidPassword, string $expectedMessage) {
    $user = new NewUser()->user;

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => $invalidPassword,
    ]);

    $response->assertRedirectBack();
    $response->assertSessionHasErrors([
        'password' => $expectedMessage,
    ]);
    $this->assertGuest();
})->with('invalid_password_data');

test('trying to sign-in with a bad password', function () {
    $user = new NewUser()->user;

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => NewUser::INVALID_PASSWORD,
    ]);

    $response->assertRedirect(route('login'));
    $this->assertGuest();
    $response->assertSessionHasErrors([
        'email' => 'Failed to login, please check your email and/or password.',
    ]);
});

test('trying to sign-in with a non existent email', function () {
    new NewUser();

    $response = $this->post(route('login.store'), [
        'email' => NewUser::NON_EXISTENT_EMAIL,
        'password' => NewUser::VALID_PASSWORD,
    ]);

    $response->assertRedirect(route('login'));
    $this->assertGuest();
    $response->assertSessionHasErrors([
        'email' => 'Failed to login, please check your email and/or password.',
    ]);
});

it('returns too many requests response when trying to brute force the login end point', function () {
    for ($i = 0; $i < 5; ++$i) {
        $response = $this->post(route('login.store'), [
            'email' => NewUser::NON_EXISTENT_EMAIL,
            'password' => NewUser::VALID_PASSWORD,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    $response = $this->post(route('login.store'), [
        'email' => NewUser::NON_EXISTENT_EMAIL,
        'password' => NewUser::VALID_PASSWORD,
    ]);

    $response->assertTooManyRequests();
    $this->assertGuest();
});
