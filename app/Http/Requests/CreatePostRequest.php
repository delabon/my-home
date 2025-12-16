<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\Requests\Shared\BasePostRequest;
use App\Models\Post;

final class CreatePostRequest extends BasePostRequest
{
    public function authorize(): bool
    {
        // For now, we don't have locked or banned users
        return $this->user()?->can('create', Post::class) ?? false;
    }
}
