<?php

declare(strict_types=1);

namespace Tests;

use Tests\Traits\WithUser;

final class NewUser
{
    use WithUser;

    public function __construct(array $attribute = [])
    {
        $this->withUser($attribute);
    }
}
