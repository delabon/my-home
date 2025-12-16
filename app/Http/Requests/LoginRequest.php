<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\LoginDTO;
use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string[]>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ];
    }

    public function toDto(): LoginDTO
    {
        /** @var array<string, string> $data */
        $data = $this->validated();

        return new LoginDTO(
            email: $data['email'],
            password: $data['password'],
        );
    }
}
