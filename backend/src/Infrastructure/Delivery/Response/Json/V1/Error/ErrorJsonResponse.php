<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Delivery\Response\Json\V1\Error;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ErrorJsonResponse extends JsonResponse
{
	public function __invoke(string $message): JsonResponse
	{
		return new JsonResponse([
			'status' => 'ERROR',
			'message' => $message
		]);
	}
}