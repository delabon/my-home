<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class EditPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('post')) ?? false;
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
        $data = $this->validated();

        return new NewPostDTO(
            title: $data['title'],
            body: $data['body'],
            status: PostStatus::from($data['status']),
        );
    }
}
