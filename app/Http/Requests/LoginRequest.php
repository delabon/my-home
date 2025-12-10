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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
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
        return new LoginDTO(
            email: $this->email,
            password: $this->password,
        );
    }
}
