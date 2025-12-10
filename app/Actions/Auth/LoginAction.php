<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\LoginDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

final class LoginAction
{
    public function execute(LoginDTO $dto): bool
    {
        $succeeded = Auth::attempt([
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        if ($succeeded) {
            Session::regenerate();
        }

        return $succeeded;
    }
}
