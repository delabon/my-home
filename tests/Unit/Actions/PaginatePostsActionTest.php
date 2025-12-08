<?php

declare(strict_types=1);

use App\Actions\PaginatePostsAction;
use App\Enums\PostStatus;
use Database\Factories\PostFactory;
use Illuminate\Pagination\Paginator;

it('paginates published posts successfully', function () {
    $posts = PostFactory::times(3)->create([
        'status' => PostStatus::Published->value
    ]);
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
        ->and($paginationData['next_page_url'])->toContain('?page=2')
        ->and($paginationData['current_page_url'])->toBe($paginationData['first_page_url'])
        ->and($paginationData['per_page'])->toBe(2)
        ->and($paginationData['data'])->toBeArray()
        ->and($paginationData['data'])->toHaveCount(2)
        ->and($paginationData['data'][0]['id'])->toBe($posts[0]->id)
        ->and($paginationData['data'][1]['id'])->toBe($posts[1]->id)
        ->and($paginationData['data'][0]['status'])->toBe(PostStatus::Published->value)
        ->and($paginationData['data'][1]['status'])->toBe(PostStatus::Published->value);
});

it('paginates posts number smaller than per page', function () {
    $post = PostFactory::new()->create([
        'status' => PostStatus::Published->value
    ]);
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
    $posts = PostFactory::times(3)->create([
        'status' => PostStatus::Published->value
    ]);
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
