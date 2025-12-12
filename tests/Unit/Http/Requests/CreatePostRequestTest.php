<?php

declare(strict_types=1);

use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use App\Http\Requests\CreatePostRequest;
use Illuminate\Validation\Rule;

it('authorizes the request', function () {
    $request = new CreatePostRequest();

    expect($request->authorize())->toBeTrue();
});

it('returns the correct rules', function () {
    $request = new CreatePostRequest();

    expect($request->rules())->toEqual([
        'title' => [
            'required',
            'string',
            'min:2',
            'max:255',
        ],
        'body' => [
            'required',
            'string',
            'min:20',
            'max:5000',
        ],
        'status' => [
            'required',
            Rule::enum(PostStatus::class),
        ]
    ]);
});

test('toDto returns a new instance of NewPostDTO', function () {
    $request = CreatePostRequest::create(
        '/',
        'POST',
        [
            'title' => 'My title 2',
            'body' => 'My body 2',
            'status' => 'published',
        ]
    );

    expect($request->toDto())->toBeInstanceOf(NewPostDTO::class);
});
