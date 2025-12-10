<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Models\Post;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Collection;

trait WithPost
{
    public Collection $posts;

    public function first(): ?Post
    {
        return $this->posts[0] ?? null;
    }

    public function withPost(array $attribute = []): Post
    {
        if ($this->posts->isEmpty()) {
            $this->createPosts(1, $attribute);
        }

        return $this->posts[0];
    }

    public function withPosts(int $times = 1, array $attribute = []): Collection
    {
        return $this->createPosts($times, $attribute);
    }

    public function createPosts(int $times = 1, array $attribute = []): Collection
    {
        if ($times === 1) {
            $this->posts = new Collection([
                PostFactory::new()->create($attribute)
            ]);
        } elseif ($times > 1) {
            $this->posts = PostFactory::times($times)->create($attribute);
        }

        return $this->posts;
    }
}
