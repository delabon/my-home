<?php

declare(strict_types=1);

use App\DTOs\LoginDTO;
use App\Http\Requests\LoginRequest;
use Tests\NewUser;

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
    $loginCredentials = NewUser::validaLoginData();
    $request = LoginRequest::create(
        '/login',
        'POST',
        $loginCredentials
    );
    $validator = validator($loginCredentials, $request->rules());
    $request->setValidator($validator);

    $dto = $request->toDto();

    expect($dto)->toBeInstanceOf(LoginDTO::class)
        ->and($dto->email)->toBe($loginCredentials['email'])
        ->and($dto->password)->toBe($loginCredentials['password']);
});
