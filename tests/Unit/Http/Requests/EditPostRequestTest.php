<?php

declare(strict_types=1);

use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use App\Http\Requests\EditPostRequest;
use Illuminate\Validation\Rule;
use Tests\NewPost;
use Tests\NewUser;

it('authorizes the request', function () {
    $user = new NewUser()->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();
    $request = EditPostRequest::create(
        route('posts.update', $post),
        'PATCH',
        NewPost::validPostData()
    );
    $request->setUserResolver(static fn () => $user);

    $request->setRouteResolver(function () use ($post) {
        return new class($post)
        {
            public function __construct(private $post) {}

            public function parameter($key, $default = null)
            {
                return $key === 'post' ? $this->post : $default;
            }
        };
    });

    expect($request->authorize())->toBeTrue();
});

it('does authorize the request when non-owner', function () {
    $user = new NewUser()->user;
    $post = new NewPost()->first();
    $request = EditPostRequest::create(
        route('posts.update', $post),
        'PATCH',
        NewPost::validPostData()
    );
    $request->setUserResolver(static fn () => $user);

    $request->setRouteResolver(function () use ($post) {
        return new class($post)
        {
            public function __construct(private $post) {}

            public function parameter($key, $default = null)
            {
                return $key === 'post' ? $this->post : $default;
            }
        };
    });

    expect($request->authorize())->toBeFalse();
});

it('returns the correct rules', function () {
    $request = new EditPostRequest();

    expect($request->rules())->toEqual([
        'title' => [
            'required',
            'string',
            'min:2',
            'max:255',
        ],
        'slug' => [
            'nullable',
            'string',
            'max:255',
            'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            Rule::unique('posts', 'slug')->ignore(null),
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
        ],
    ]);
});

test('toDto returns a new instance of NewPostDTO', function () {
    $data = NewPost::validPostData();
    $request = EditPostRequest::create(
        '/',
        'POST',
        $data
    );

    $validator = validator($data, $request->rules());
    $request->setValidator($validator);

    $dto = $request->toDto();

    expect($dto)->toBeInstanceOf(NewPostDTO::class)
        ->and($dto->title)->toBe($data['title'])
        ->and($dto->slug)->toBe($data['slug'])
        ->and($dto->body)->toBe($data['body'])
        ->and($dto->status)->toBe(PostStatus::from($data['status']));
});
