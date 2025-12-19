<?php

declare(strict_types=1);

namespace App\Http\Requests\Shared;

use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
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
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('posts', 'slug')->ignore($this->route('post')?->id),
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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->slug ?? $this->title ?? ''),
        ]);
    }

    final public function toDto(): NewPostDTO
    {
        /** @var array<string, string> $data */
        $data = $this->validated();

        return new NewPostDTO(
            title: $data['title'],
            slug: $data['slug'],
            body: $data['body'],
            status: PostStatus::from($data['status']),
        );
    }
}
