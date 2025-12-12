<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Enums\PostStatus;
use App\Models\Post;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Collection;

trait WithPost
{
    public const string VALID_TITLE = 'This is a valid post title';

    public const string VALID_BODY = 'This is a valid post body it should be more than 20 chars';

    public const string VALID_STATUS = PostStatus::Published->value;

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

    public static function validPostData(): array
    {
        return [
            'title' => self::VALID_TITLE,
            'body' => self::VALID_BODY,
            'status' => self::VALID_STATUS,
        ];
    }
}
