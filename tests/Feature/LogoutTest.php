<?php

declare(strict_types=1);

use Tests\NewUser;

it('logs out a user successfully', function () {
    $user = new NewUser()->user;

    $this->actingAs($user);

    $oldSessionId = session()->id();

    $response = $this->post(route('logout'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
    expect(auth()->user())->toBeNull()
        ->and($oldSessionId)->not()->toBe(session()->id());
});

it('redirects to login page when trying to log out when already logged out', function () {
    $response = $this->post(route('logout'));

    $response->assertRedirect(route('login'));
});

it('returns too many requests response when trying to log out more than the rate limit', function () {
    for ($i = 0; $i < 5; $i++) {
        $response = $this->post(route('logout'));
        $response->assertRedirect(route('login'));
    }

    $response = $this->post(route('logout'));
    $response->assertTooManyRequests();
});
