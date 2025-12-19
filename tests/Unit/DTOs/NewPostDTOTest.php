<?php

declare(strict_types=1);

use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use Illuminate\Contracts\Support\Arrayable;

it('creates an instance of Arrayable', function () {
    $dto = new NewPostDTO(
        title: 'My title',
        slug: 'my-post',
        body: 'My body',
        status: PostStatus::Draft,
    );

    expect($dto)->toBeInstanceOf(Arrayable::class);
});

test('toArray returns an array of properties and their values', function () {
    $dto = new NewPostDTO(
        title: 'My title 2',
        slug: 'my-post2',
        body: 'My body 2',
        status: PostStatus::Published,
    );

    expect($dto->toArray())->toEqual([
        'title' => 'My title 2',
        'slug' => 'my-post2',
        'body' => 'My body 2',
        'status' => PostStatus::Published,
    ]);
});
