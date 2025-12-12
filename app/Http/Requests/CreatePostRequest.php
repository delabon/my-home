<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        // For now, we don't have locked or banned users
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
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
            ]
        ];
    }

    public function toDto(): NewPostDTO
    {
        return new NewPostDTO(
            title: $this->title,
            body: $this->body,
            status: $this->status,
        );
    }
}
