<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia;

it('renders the home page component successfully', function () {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertInertia(function (AssertableInertia $component) {
        $component->component('Home');
    });
});
