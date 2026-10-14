<?php

declare(strict_types=1);

use App\Http\Responses\FormatJsonError;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

it('leaves non JSON responses untouched', function (): void {
    $response = new Response('<h1>Not Found</h1>', 404);

    expect((new FormatJsonError)($response))->toBe($response)
        ->and($response->getContent())->toBe('<h1>Not Found</h1>');
});

it('leaves successful JSON responses untouched', function (): void {
    $response = new JsonResponse(['ok' => true]);

    expect((new FormatJsonError)($response)->getContent())->toBe('{"ok":true}');
});

it('reshapes a validation error', function (): void {
    $response = new JsonResponse(['message' => 'The name field is required.', 'errors' => ['name' => ['Required']]], 422);

    expect((new FormatJsonError)($response)->getContent())
        ->toBe('{"success":false,"message":"The name field is required.","errors":{"name":["Required"]}}');
});

it('falls back to the status text when there is no message', function (): void {
    $response = new JsonResponse(null, 404);

    expect((new FormatJsonError)($response)->getContent())
        ->toBe('{"success":false,"message":"Not Found","errors":{}}');
});

it('falls back to a generic message for unknown status codes', function (): void {
    $response = new JsonResponse(['message' => ''], 499);

    expect((new FormatJsonError)($response)->getContent())
        ->toBe('{"success":false,"message":"Error","errors":{}}');
});

it('keeps debug details separate', function (): void {
    $response = new JsonResponse(['message' => 'Boom', 'exception' => 'RuntimeException'], 500);

    expect((new FormatJsonError)($response)->getContent())
        ->toBe('{"success":false,"message":"Boom","errors":{},"debug":{"exception":"RuntimeException"}}');
});
