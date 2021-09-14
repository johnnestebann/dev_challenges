<?php

namespace Workana\Infrastructure\Delivery\Action\V1\Error;

use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;
use Workana\Infrastructure\Delivery\Response\Json\V1\Error\ErrorJsonResponse;

class ErrorAction
{
    private ErrorJsonResponse $errorJsonResponse;

    public function __construct(ErrorJsonResponse $errorJsonResponse)
    {
        $this->errorJsonResponse = $errorJsonResponse;
    }

    public function __invoke(Throwable $exception): JsonResponse
    {
        return ($this->errorJsonResponse)($exception->getMessage());
    }
}
