<?php

declare(strict_types=1);

use Database\Factories\UserFactory;

it('signs-in a user successfully', function () {
    $password = '12341234';
    $user =  UserFactory::new()->create([
        'password' => $password,
    ]);

    $this->assertGuest();

    $page = visit(route('login'));

    $page->fill('[name="email"]', $user->email)
        ->fill('[name="password"]', $password)
        ->click('Sign-In');

    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id);
});

it('fails when trying to sign-in a user with invalid email', function () {
    $page = visit(route('login'));

    $page->fill('[name="email"]', 'invalid-email@test.com')
        ->fill('[name="password"]', '12341234')
        ->click('Sign-In')
        ->assertSee('Failed to login, please check your email and/or password.');

    $this->assertGuest();
});

it('fails when trying to sign-in a user with invalid password', function () {
    $password = '12341234';
    $user =  UserFactory::new()->create([
        'password' => $password,
    ]);

    $page = visit(route('login'));

    $page->fill('[name="email"]', $user->email)
        ->fill('[name="password"]', 'kfcswerfd')
        ->click('Sign-In')
        ->assertSee('Failed to login, please check your email and/or password.');

    $this->assertGuest();
});
