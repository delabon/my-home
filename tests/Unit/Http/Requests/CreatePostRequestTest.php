<?php

declare(strict_types=1);

use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use App\Http\Requests\CreatePostRequest;
use Illuminate\Validation\Rule;
use Tests\NewPost;
use Tests\NewUser;

it('authorizes the request', function () {
    $request = new CreatePostRequest();
    $request->setUserResolver(static fn () => new NewUser()->user);

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
    $data = NewPost::validPostData();
    $request = CreatePostRequest::create(
        '/',
        'POST',
        $data
    );

    $validator = validator($data, $request->rules());
    $request->setValidator($validator);

    $dto = $request->toDto();

    expect($dto)->toBeInstanceOf(NewPostDTO::class)
        ->and($dto->title)->toBe($data['title'])
        ->and($dto->body)->toBe($data['body'])
        ->and($dto->status)->toBe(PostStatus::from($data['status']));
});
