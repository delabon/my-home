<?php

declare(strict_types=1);

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
