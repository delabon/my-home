<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\PostStatus;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class NewPostDTO implements Arrayable
{
    public function __construct(
        public string $title,
        public string $slug,
        public string $body,
        public PostStatus $status,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'status' => $this->status,
        ];
    }
}
