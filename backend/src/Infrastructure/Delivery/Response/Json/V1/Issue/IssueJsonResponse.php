<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Delivery\Response\Json\V1\Issue;

use Symfony\Component\HttpFoundation\JsonResponse;
use Workana\Domain\Model\Issue\Issue;

class IssueJsonResponse extends JsonResponse
{
    public function __invoke(Issue $issue): JsonResponse
    {
        return new JsonResponse([
            'status' => 'OK',
            'data' => $issue->toArray()
        ]);
    }
}
