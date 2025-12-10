<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Eloquent\Collection;
use Tests\Traits\WithPost;

final class NewPost
{
    use WithPost;

    public function __construct(array $attribute = [], int $times = 1)
    {
        $this->posts = new Collection();

        if ($times === 1) {
            $this->withPost($attribute);
        } elseif ($times > 1) {
            $this->withPosts($times, $attribute);
        }
    }
}
