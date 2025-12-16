<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Models\User;
use Database\Factories\UserFactory;
use RuntimeException;
use Tests\TestCase;

trait WithUser
{
    public const string VALID_PASSWORD = '12345432';

    public const string INVALID_PASSWORD = 'wrong-password';

    public const string NON_EXISTENT_EMAIL = 'non-existent@test.cc';

    public const string VALID_EMAIL = 'john@doe.cc';

    public User $user;

    public static function validaLoginData(): array
    {
        return [
            'email' => self::VALID_EMAIL,
            'password' => self::VALID_PASSWORD,
        ];
    }

    public function withUser(array $attribute = []): User
    {
        $this->user = $this->user ?? $this->createUser($attribute);

        return $this->user;
    }

    public function createUser(array $attribute = []): User
    {
        $attribute['password'] = $attribute['password'] ?? self::VALID_PASSWORD;
        $attribute['email'] = $attribute['email'] ?? self::VALID_EMAIL;

        return UserFactory::new()->create($attribute);
    }

    public function login(TestCase $testCase): self
    {
        if (! $this->user) {
            throw new RuntimeException('You need to create a new user first.');
        }

        $testCase->actingAs($this->user);

        return $this;
    }
}
