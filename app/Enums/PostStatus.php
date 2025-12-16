<?php

declare(strict_types=1);

namespace App\Enums;

enum PostStatus: string
{
    case Published = 'published';
    case Draft = 'draft';

    public function label(): string
    {
        return match ($this) {
            self::Published => 'Published',
            self::Draft => 'Draft',
        };
    }

    /**
     * @return array<int, string[]>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->map(static fn (self $case) => [
                'value' => $case->value,
                'label' => $case->label()
            ])
            ->all();
    }
}
