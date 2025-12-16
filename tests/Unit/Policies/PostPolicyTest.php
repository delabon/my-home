<?php

declare(strict_types=1);

use App\Models\User;
use App\Policies\PostPolicy;
use Tests\NewPost;
use Tests\NewUser;

it('authorizes creating a post', function () {
    $policy = new PostPolicy();

    expect($policy->create(new User()))->toBeTrue();
});

it('authorizes editing a post', function () {
    $policy = new PostPolicy();
    $user = new NewUser()->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    expect($policy->update($user, $post))->toBeTrue();
});
