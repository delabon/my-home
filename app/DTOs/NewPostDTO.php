<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\PostStatus;
use Illuminate\Contracts\Support\Arrayable;

final readonly class NewPostDTO implements Arrayable
{
    public function __construct(
        public string $title,
        public string $body,
        public PostStatus $status,
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'status' => $this->status,
        ];
    }
}
