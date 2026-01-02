<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderDirection: string
{
    case Desc = 'desc';
    case Asc = 'asc';
}
