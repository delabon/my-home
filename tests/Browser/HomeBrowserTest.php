<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use Database\Factories\PostFactory;

test('page passes smoke test (no JS errors and console logs)', function () {
    visit('/')->assertNoSmoke();
});

it('renders the homepage title correctly', function () {
    $page = visit(route('home'));

    $page->assertTitle(config('app.name'));
});

it('shows no posts yet message when there are no posts', function () {
    $page = visit(route('home'));

    $page->assertSee('No posts yet!');

    PostFactory::times(3)->create([
        'status' => PostStatus::Draft->value,
    ]);

    $page = visit(route('home'));

    $page->assertSee('No posts yet!');
});

it('displays the published blog posts', function () {
    $postCount = 3;
    $posts = PostFactory::times($postCount)->create([
        'status' => PostStatus::Published,
    ]);

    $page = visit(route('home'));

    for ($i = 0; $i < 3; ++$i) {
        $page->assertSee($posts[$i]->title);
    }

    $page->assertSourceMissing('<a href="'.route('home',['page' => 1,]).'">Prev</a>')
        ->assertSourceMissing('<a href="'.route('home',['page' => 2,]).'">Next</a>');

});

it('paginates the published blog posts', function () {
    $postCount = 11;
    $posts = PostFactory::times($postCount)->create([
        'status' => PostStatus::Published,
    ]);

    $page = visit(route('home'));

    for ($i = 0; $i < ($postCount - 1); ++$i) {
        $page->assertSee($posts[$i]->title);
    }

    $page->assertDontSee($posts[10]->title)
        ->assertDontSeeLink('Prev')
        ->assertSeeLink('Next')
        ->click('Next')
        ->assertSee($posts[10]->title)
        ->assertSeeLink('Prev')
        ->assertDontSeeLink('Next');
});
