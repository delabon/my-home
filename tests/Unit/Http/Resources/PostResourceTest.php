<?php

declare(strict_types=1);

use App\Http\Resources\PostResource;
use Tests\NewPost;

it('returns the resource array', function () {
    $post = new NewPost()
        ->first();
    $resource = new PostResource($post);

    $resourceData = $resource->toArray(\Illuminate\Http\Request::createFromGlobals());

    expect($resourceData)->toBeArray()
        ->and($resourceData)->toEqual([
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'formatted_created_at' => $post->formatted_created_at,
        ]);
});
