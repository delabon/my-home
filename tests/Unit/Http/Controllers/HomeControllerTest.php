<?php

declare(strict_types=1);

use App\Actions\Posts\PaginatePostsAction;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Response;
use Inertia\Response as InertiaResponse;

it('returns an Inertia response successfully', function () {
    $request = Request::create(route('home'));
    $action = new PaginatePostsAction();
    $controller = new HomeController();

    $inertiaResponse = $controller($request, $action);
    $response = $inertiaResponse->toResponse($request);

    expect($inertiaResponse)->toBeInstanceOf(InertiaResponse::class)
        ->and($response)->toBeInstanceOf(Response::class)
        ->and($response->status())->toBe(Response::HTTP_OK);

});
