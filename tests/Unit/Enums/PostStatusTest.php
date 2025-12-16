<?php

declare(strict_types=1);

use App\Enums\PostStatus;

it('returns the correct label', function () {
    expect(PostStatus::Published->label())->toBe('Published');
});

it('returns options for the Select component', function () {
    expect(PostStatus::options())->toBe([
        [
            'value' => 'published',
            'label' => 'Published',
        ],
        [
            'value' => 'draft',
            'label' => 'Draft',
        ],
    ]);
});
