<?php

declare(strict_types=1);

use Database\Factories\UserFactory;

it('signs in successfully', function () {
    $password = '12345678';
    $user = UserFactory::new()->create([
        'email' => 'john@doe.com',
        'password' => $password
    ]);

    $oldSessionId = session()->id();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => $password
    ]);

    $newSessionId = session()->id();

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id)
        ->and($oldSessionId)->not->toBe($newSessionId);
});

it('signs in a different user successfully', function () {
    $password = 'kwer1234';
    $user = UserFactory::new()->create([
        'email' => 'mike@jordon.com',
        'password' => $password
    ]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => $password
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id);
});

test('trying to sign-in while already signed-in returns a redirect to login page response', function () {
    $password = 'kwer1234';
    $user = UserFactory::new()->create([
        'email' => 'mike@jordon.com',
        'password' => $password
    ]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => $password
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id);

    $anotherPassword = '87651234';
    $anotherUser = UserFactory::new()->create([
        'email' => 'lina@doe.com',
        'password' => $password
    ]);

    $newResponse = $this->post(route('login.store'), [
        'email' => $anotherUser->email,
        'password' => $anotherPassword
    ]);

    $newResponse->assertRedirectToRoute('login');
    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id);
});

dataset('invalid_email_data', [
    [
        '',
        'The email field is required.'
    ],
    [
        'john.doe',
        'The email field must be a valid email address.'
    ],
    [
        '@',
        'The email field must be a valid email address.'
    ],
    [
        '@test.com',
        'The email field must be a valid email address.'
    ],
]);

it('fails with invalid email addresses', function (string $invalidEmail, string $expectedMessage) {
    $password = '12345678';
    UserFactory::new()->create([
        'email' => 'john.doe@test.com',
        'password' => $password
    ]);

    $response = $this->post(route('login.store'), [
        'email' => $invalidEmail,
        'password' => $password
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
        'The password field is required.'
    ],
    [
        true,
        'The password field must be at least 8 characters.'
    ],
    [
        '123',
        'The password field must be at least 8 characters.'
    ],
]);

it('fails with invalid passwords', function (mixed $invalidPassword, string $expectedMessage) {
    $email = 'john.doe@test.com';
    UserFactory::new()->create([
        'email' => $email,
        'password' => '12341234',
    ]);

    $response = $this->post(route('login.store'), [
        'email' => $email,
        'password' => $invalidPassword
    ]);

    $response->assertRedirectBack();
    $response->assertSessionHasErrors([
        'password' => $expectedMessage,
    ]);
    $this->assertGuest();
})->with('invalid_password_data');

test('trying to sign-in with a bad password', function () {
    $password = 'kwer1234';
    $user = UserFactory::new()->create([
        'email' => 'mike@jordon.com',
        'password' => $password
    ]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'bad-password'
    ]);

    $response->assertRedirect(route('login'));
    $this->assertGuest();
    $response->assertSessionHasErrors([
        'email' => 'Failed to login, please check your email and/or password.',
    ]);
});

test('trying to sign-in with a bad email', function () {
    $password = 'kwer1234';
    UserFactory::new()->create([
        'email' => 'mike@jordon.com',
        'password' => $password
    ]);

    $response = $this->post(route('login.store'), [
        'email' => 'bad.email@test.cc',
        'password' => $password,
    ]);

    $response->assertRedirect(route('login'));
    $this->assertGuest();
    $response->assertSessionHasErrors([
        'email' => 'Failed to login, please check your email and/or password.',
    ]);
});
