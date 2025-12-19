<?php

declare(strict_types=1);

dataset('invalid_title_data', [
    [
        '',
        'The title field is required.',
    ],
    [
        'U',
        'The title field must be at least 2 characters.',
    ],
    [
        str_repeat('a', 256),
        'The title field must not be greater than 255 characters.',
    ],
]);

dataset('invalid_slug_data', [
    [
        str_repeat('a', 256),
        'The slug field must not be greater than 255 characters.',
    ],
]);

dataset('invalid_body_data', [
    [
        '',
        'The body field is required.',
    ],
    [
        'ABCD',
        'The body field must be at least 20 characters.',
    ],
    [
        str_repeat('a', 5001),
        'The body field must not be greater than 5000 characters.',
    ],
]);

dataset('invalid_status_data', [
    [
        '',
        'The status field is required.',
    ],
    [
        'ABCD',
        'The selected status is invalid.',
    ],
]);
