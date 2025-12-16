<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\Requests\Shared\BasePostRequest;

final class EditPostRequest extends BasePostRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('post')) ?? false;
    }
}
