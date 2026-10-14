<?php

declare(strict_types=1);

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

function apiController(): object
{
    return new class extends Controller
    {
        /**
         * @param  array<string, mixed>  $arguments
         */
        public function call(string $method, array $arguments = []): JsonResponse
        {
            return $this->{$method}(...$arguments);
        }
    };
}

it('returns a success reply with data and message', function (): void {
    $response = apiController()->call('success', ['data' => ['id' => 1], 'message' => 'Loaded']);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->getData(true))->toBe([
            'success' => true,
            'message' => 'Loaded',
            'data' => ['id' => 1],
        ]);
});

it('returns a success reply with defaults', function (): void {
    $response = apiController()->call('success');

    expect($response->getData(true))->toBe([
        'success' => true,
        'message' => 'OK',
        'data' => null,
    ]);
});

it('returns a created reply with a resource', function (): void {
    $response = apiController()->call('created', ['data' => JsonResource::make(['id' => 7])]);

    expect($response->getStatusCode())->toBe(201)
        ->and($response->getData(true))->toBe([
            'success' => true,
            'message' => 'Created',
            'data' => ['id' => 7],
        ]);
});

it('returns an empty no content reply', function (): void {
    $response = apiController()->call('noContent');

    expect($response->getStatusCode())->toBe(204)
        ->and($response->getContent())->toBe('{}');
});

it('returns an error reply with errors as an object', function (): void {
    $response = apiController()->call('error', ['message' => 'Nope', 'status' => 409, 'errors' => ['title' => ['Taken']]]);

    expect($response->getStatusCode())->toBe(409)
        ->and($response->getData(true))->toBe([
            'success' => false,
            'message' => 'Nope',
            'errors' => ['title' => ['Taken']],
        ])
        ->and(apiController()->call('error', ['message' => 'Bad'])->getContent())
        ->toBe('{"success":false,"message":"Bad","errors":{}}');
});
