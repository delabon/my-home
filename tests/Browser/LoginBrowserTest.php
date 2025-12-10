<?php

declare(strict_types=1);

use Tests\NewUser;

it('signs-in a user successfully', function () {
    $user = new NewUser()->user;

    $this->assertGuest();

    $page = visit(route('login'));

    $page->fill('[name="email"]', $user->email)
        ->fill('[name="password"]', NewUser::VALID_PASSWORD)
        ->click('Sign-In');

    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id);
});

it('fails when trying to sign-in a user with invalid email', function () {
    $page = visit(route('login'));

    $page->fill('[name="email"]', 'invalid-email@test.com')
        ->fill('[name="password"]', NewUser::VALID_PASSWORD)
        ->click('Sign-In')
        ->assertSee('Failed to login, please check your email and/or password.');

    $this->assertGuest();
});

it('fails when trying to sign-in a user with invalid password', function () {
    $user =  new NewUser()->user;

    $page = visit(route('login'));

    $page->fill('[name="email"]', $user->email)
        ->fill('[name="password"]', NewUser::INVALID_PASSWORD)
        ->click('Sign-In')
        ->assertSee('Failed to login, please check your email and/or password.');

    $this->assertGuest();
});
