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

it('authorizes updating a post', function () {
    $policy = new PostPolicy();
    $user = new NewUser()->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    expect($policy->update($user, $post))->toBeTrue();
});

it('does not authorize updating a post for a non-owner', function () {
    $policy = new PostPolicy();
    $nonOwner = new NewUser()->user;
    $post = new NewPost()->first();

    expect($policy->update($nonOwner, $post))->toBeFalse();
});

it('authorizes editing a post', function () {
    $policy = new PostPolicy();
    $user = new NewUser()->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    expect($policy->edit($user, $post))->toBeTrue();
});

it('does not authorize editing a post for a non-owner', function () {
    $policy = new PostPolicy();
    $nonOwner = new NewUser()->user;
    $post = new NewPost()->first();

    expect($policy->edit($nonOwner, $post))->toBeFalse();
});

it('authorizes soft deleting a post', function () {
    $policy = new PostPolicy();
    $user = new NewUser()->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    expect($policy->softDelete($user, $post))->toBeTrue();
});

it('does not authorize soft deleting a post for a non-owner', function () {
    $policy = new PostPolicy();
    $nonOwner = new NewUser()->user;
    $post = new NewPost()->first();

    expect($policy->softDelete($nonOwner, $post))->toBeFalse();
});
