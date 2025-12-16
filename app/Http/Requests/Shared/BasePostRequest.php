<?php

declare(strict_types=1);

namespace App\Http\Requests\Shared;

use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BasePostRequest extends FormRequest
{
    abstract public function authorize(): bool;

    /**
     * @return array<string, array<mixed>>
     */
    final public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'body' => [
                'required',
                'string',
                'min:20',
                'max:5000',
            ],
            'status' => [
                'required',
                Rule::enum(PostStatus::class),
            ],
        ];
    }

    final public function toDto(): NewPostDTO
    {
        /** @var array<string, string> $data */
        $data = $this->validated();

        return new NewPostDTO(
            title: $data['title'],
            body: $data['body'],
            status: PostStatus::from($data['status']),
        );
    }
}
