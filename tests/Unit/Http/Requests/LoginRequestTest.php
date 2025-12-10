<?php

declare(strict_types=1);

use App\DTOs\LoginDTO;
use App\Http\Requests\LoginRequest;

it('authorizes a request', function () {
    $request = new LoginRequest();

    expect($request->authorize())->toBeTrue();
});

it('returns the correct rules', function () {
    $request = new LoginRequest();

    expect($request->rules())->toEqual([
        'email' => [
            'required',
            'email',
        ],
        'password' => [
            'required',
            'string',
            'min:8',
        ],
    ]);
});

test('toDto method returns a new instance of LoginDTO', function () {
    $email = 'john@doe.cc';
    $password = '12341234';
    $request = LoginRequest::create(
        '/login',
        'POST',
        [
            'email' => $email,
            'password' => $password,
        ]
    );

    $dto = $request->toDto();

    expect($dto)->toBeInstanceOf(LoginDTO::class)
        ->and($dto->email)->toBe($email)
        ->and($dto->password)->toBe($password);
});
