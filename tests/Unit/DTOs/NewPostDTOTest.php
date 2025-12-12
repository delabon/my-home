<?php

declare(strict_types=1);

use App\DTOs\NewPostDTO;
use Illuminate\Contracts\Support\Arrayable;

it('creates an instance of Arrayable', function () {
    $dto = new NewPostDTO(
        title: 'My title',
        body: 'My body',
        status: 'draft',
    );

    expect($dto)->toBeInstanceOf(Arrayable::class);
});

test('toArray returns an array of properties and their values', function () {
    $dto = new NewPostDTO(
        title: 'My title 2',
        body: 'My body 2',
        status: 'published',
    );

    expect($dto->toArray())->toEqual([
        'title' => 'My title 2',
        'body' => 'My body 2',
        'status' => 'published',
    ]);
});
