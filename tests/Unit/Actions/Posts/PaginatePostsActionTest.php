<?php

declare(strict_types=1);

use App\Actions\Posts\PaginatePostsAction;
use App\Enums\OrderDirection;
use App\Enums\PostStatus;
use Illuminate\Pagination\Paginator;
use Tests\NewPost;

it('paginates posts with any status successfully', function () {
    $publishedPost = new NewPost([
        'status' => PostStatus::Published->value,
    ], 2)->posts;
    $draftPosts = new NewPost([
        'status' => PostStatus::Draft->value,
    ], 3)->posts;
    $action = new PaginatePostsAction();

    $paginatedPosts = $action->execute(perPage: 4, status: null);

    $paginationData = $paginatedPosts->toArray();

    expect($paginatedPosts)->toBeInstanceOf(Paginator::class)
        ->and($paginationData)->toHaveKeys([
            'current_page',
            'current_page_url',
            'data',
            'first_page_url',
            'next_page_url',
            'per_page',
        ])
        ->and($paginationData['current_page'])->toBeOne()
        ->and($paginationData['first_page_url'])->toContain('?page=1')
        ->and($paginationData['next_page_url'])->toContain('?page=2')
        ->and($paginationData['current_page_url'])->toBe($paginationData['first_page_url'])
        ->and($paginationData['per_page'])->toBe(4)
        ->and($paginationData['data'])->toBeArray()
        ->and($paginationData['data'])->toHaveCount(4)
        ->and($paginationData['data'][0]['id'])->toBe($publishedPost[0]->id)
        ->and($paginationData['data'][1]['id'])->toBe($publishedPost[1]->id)
        ->and($paginationData['data'][0]['status'])->toBe(PostStatus::Published->value)
        ->and($paginationData['data'][1]['status'])->toBe(PostStatus::Published->value)
        ->and($paginationData['data'][2]['id'])->toBe($draftPosts[0]->id)
        ->and($paginationData['data'][3]['id'])->toBe($draftPosts[1]->id)
        ->and($paginationData['data'][2]['status'])->toBe(PostStatus::Draft->value)
        ->and($paginationData['data'][3]['status'])->toBe(PostStatus::Draft->value);
});

it('paginates published posts successfully', function () {
    $publishedPosts = new NewPost([
        'status' => PostStatus::Published->value,
    ], 3)->posts;
    new NewPost([
        'status' => PostStatus::Draft->value,
    ], 2)->posts;
    $action = new PaginatePostsAction();

    $paginatedPosts = $action->execute(perPage: 2, status: PostStatus::Published);

    $paginationData = $paginatedPosts->toArray();

    expect($paginatedPosts)->toBeInstanceOf(Paginator::class)
        ->and($paginationData)->toHaveKeys([
            'current_page',
            'current_page_url',
            'data',
            'first_page_url',
            'next_page_url',
            'per_page',
        ])
        ->and($paginationData['current_page'])->toBeOne()
        ->and($paginationData['first_page_url'])->toContain('?page=1')
        ->and($paginationData['next_page_url'])->toContain('?page=2')
        ->and($paginationData['current_page_url'])->toBe($paginationData['first_page_url'])
        ->and($paginationData['per_page'])->toBe(2)
        ->and($paginationData['data'])->toBeArray()
        ->and($paginationData['data'])->toHaveCount(2)
        ->and($paginationData['data'][0]['id'])->toBe($publishedPosts[0]->id)
        ->and($paginationData['data'][1]['id'])->toBe($publishedPosts[1]->id)
        ->and($paginationData['data'][0]['status'])->toBe(PostStatus::Published->value)
        ->and($paginationData['data'][1]['status'])->toBe(PostStatus::Published->value);
});

it('paginates draft posts successfully', function () {
    new NewPost([
        'status' => PostStatus::Published->value,
    ], 2)->posts;
    $draftPosts = new NewPost([
        'status' => PostStatus::Draft->value,
    ], 3)->posts;
    $action = new PaginatePostsAction();

    $paginatedPosts = $action->execute(perPage: 2, status: PostStatus::Draft);

    $paginationData = $paginatedPosts->toArray();

    expect($paginatedPosts)->toBeInstanceOf(Paginator::class)
        ->and($paginationData)->toHaveKeys([
            'current_page',
            'current_page_url',
            'data',
            'first_page_url',
            'next_page_url',
            'per_page',
        ])
        ->and($paginationData['current_page'])->toBeOne()
        ->and($paginationData['first_page_url'])->toContain('?page=1')
        ->and($paginationData['next_page_url'])->toContain('?page=2')
        ->and($paginationData['current_page_url'])->toBe($paginationData['first_page_url'])
        ->and($paginationData['per_page'])->toBe(2)
        ->and($paginationData['data'])->toBeArray()
        ->and($paginationData['data'])->toHaveCount(2)
        ->and($paginationData['data'][0]['id'])->toBe($draftPosts[0]->id)
        ->and($paginationData['data'][1]['id'])->toBe($draftPosts[1]->id)
        ->and($paginationData['data'][0]['status'])->toBe(PostStatus::Draft->value)
        ->and($paginationData['data'][1]['status'])->toBe(PostStatus::Draft->value);
});

it('paginates posts number smaller than per page', function () {
    $post = new NewPost([
        'status' => PostStatus::Published->value,
    ])->first();
    $action = new PaginatePostsAction();

    $paginatedPosts = $action->execute(perPage: 33);

    $paginationData = $paginatedPosts->toArray();

    expect($paginatedPosts)->toBeInstanceOf(Paginator::class)
        ->and($paginationData)->toHaveKeys([
            'current_page',
            'current_page_url',
            'data',
            'first_page_url',
            'next_page_url',
            'per_page',
        ])
        ->and($paginationData['current_page'])->toBeOne()
        ->and($paginationData['first_page_url'])->toContain('?page=1')
        ->and($paginationData['next_page_url'])->toBeNull()
        ->and($paginationData['current_page_url'])->toBe($paginationData['first_page_url'])
        ->and($paginationData['per_page'])->toBe(33)
        ->and($paginationData['data'])->toBeArray()
        ->and($paginationData['data'])->toHaveCount(1)
        ->and($paginationData['data'][0]['id'])->toBe($post->id)
        ->and($paginationData['data'][0]['status'])->toBe(PostStatus::Published->value);
});

it('paginates successfully even without posts', function () {
    $action = new PaginatePostsAction();

    $paginatedPosts = $action->execute(perPage: 2);

    $paginationData = $paginatedPosts->toArray();

    expect($paginatedPosts)->toBeInstanceOf(Paginator::class)
        ->and($paginationData)->toHaveKeys([
            'current_page',
            'current_page_url',
            'data',
            'first_page_url',
            'next_page_url',
            'per_page',
        ])
        ->and($paginationData['current_page'])->toBeOne()
        ->and($paginationData['first_page_url'])->toContain('?page=1')
        ->and($paginationData['next_page_url'])->toBeNull()
        ->and($paginationData['current_page_url'])->toBe($paginationData['first_page_url'])
        ->and($paginationData['per_page'])->toBe(2)
        ->and($paginationData['data'])->toBeArray()
        ->and($paginationData['data'])->toHaveCount(0);
});

it('paginates the next page', function () {
    $posts = new NewPost([
        'status' => PostStatus::Published->value,
    ], 3)->posts;
    Paginator::currentPageResolver(static fn () => 2);
    $action = new PaginatePostsAction();

    $paginatedPosts = $action->execute(perPage: 2);

    $paginationData = $paginatedPosts->toArray();

    expect($paginatedPosts)->toBeInstanceOf(Paginator::class)
        ->and($paginationData)->toHaveKeys([
            'current_page',
            'current_page_url',
            'data',
            'first_page_url',
            'next_page_url',
            'per_page',
        ])
        ->and($paginationData['current_page'])->toBe(2)
        ->and($paginationData['first_page_url'])->toContain('?page=1')
        ->and($paginationData['next_page_url'])->toBeNull()
        ->and($paginationData['current_page_url'])->toContain('?page=2')
        ->and($paginationData['per_page'])->toBe(2)
        ->and($paginationData['data'])->toBeArray()
        ->and($paginationData['data'])->toHaveCount(1)
        ->and($paginationData['data'][0]['id'])->toBe($posts[2]->id)
        ->and($paginationData['data'][0]['status'])->toBe(PostStatus::Published->value);
});

it('paginates posts by field in descending order', function () {
    $postOne = new NewPost([
        'status' => PostStatus::Published->value,
        'published_at' => now()->subDay(),
    ])->first();
    $postTwo = new NewPost([
        'status' => PostStatus::Published->value,
        'published_at' => now(),
    ])->first();
    $action = new PaginatePostsAction();

    $paginatedPosts = $action->execute(
        orderColumn: 'published_at',
        orderDirection: OrderDirection::Desc
    );

    $paginationData = $paginatedPosts->toArray();

    expect($paginatedPosts)->toBeInstanceOf(Paginator::class)
        ->and($paginationData)->toHaveKeys([
            'data',
        ])
        ->and($paginationData['data'])->toBeArray()
        ->and($paginationData['data'])->toHaveCount(2)
        ->and($paginationData['data'][0]['id'])->toBe($postTwo->id)
        ->and($paginationData['data'][1]['id'])->toBe($postOne->id);
});

it('paginates posts by field in ascending order', function () {
    $postOne = new NewPost()->first();
    $postTwo = new NewPost()->first();
    $action = new PaginatePostsAction();

    $paginatedPosts = $action->execute(
        orderColumn: 'id',
        orderDirection: OrderDirection::Asc
    );

    $paginationData = $paginatedPosts->toArray();

    expect($paginatedPosts)->toBeInstanceOf(Paginator::class)
        ->and($paginationData)->toHaveKeys([
            'data',
        ])
        ->and($paginationData['data'])->toBeArray()
        ->and($paginationData['data'])->toHaveCount(2)
        ->and($paginationData['data'][0]['id'])->toBe($postOne->id)
        ->and($paginationData['data'][1]['id'])->toBe($postTwo->id);
});
