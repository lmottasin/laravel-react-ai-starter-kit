<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reshapes Laravel's JSON error responses (422, 401, 403, 404, 500...)
 * into the same shape the ApiResponse trait uses.
 */
final class FormatJsonError
{
    public function __invoke(Response $response): Response
    {
        if (! $response instanceof JsonResponse || $response->isSuccessful()) {
            return $response;
        }

        $payload = $response->getData(true);
        $payload = is_array($payload) ? $payload : [];

        $message = $payload['message'] ?? null;
        $errors = $payload['errors'] ?? null;

        $body = [
            'success' => false,
            'message' => is_string($message) && $message !== '' ? $message : Response::$statusTexts[$response->getStatusCode()] ?? 'Error',
            'errors' => (object) (is_array($errors) ? $errors : []),
        ];

        $debug = array_diff_key($payload, ['message' => true, 'errors' => true]);

        if ($debug !== []) {
            $body['debug'] = $debug;
        }

        return $response->setData($body);
    }
}
