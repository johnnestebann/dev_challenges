<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Delivery\Action\V1\Issue;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Workana\Application\Service\V1\Issue\GetIssueByIdService;
use Workana\Infrastructure\Delivery\Response\Json\V1\Error\ErrorJsonResponse;
use Workana\Infrastructure\Delivery\Response\Json\V1\Issue\IssueJsonResponse;

final class GetIssueStatusAction
{
    private GetIssueByIdService $getIssueByIdService;

    private ErrorJsonResponse $errorJsonResponse;

    private IssueJsonResponse $issueJsonResponse;

    public function __construct(
        GetIssueByIdService $getIssueByIdService,
        ErrorJsonResponse $errorJsonResponse,
        IssueJsonResponse $issueJsonResponse
    ) {
        $this->getIssueByIdService = $getIssueByIdService;
        $this->errorJsonResponse = $errorJsonResponse;
        $this->issueJsonResponse = $issueJsonResponse;
    }

    public function __invoke(int $issueId): JsonResponse
    {
        try {
            $issue = ($this->getIssueByIdService)($issueId);
        } catch (Exception $e) {
            return ($this->errorJsonResponse)($e->getMessage());
        }

        return ($this->issueJsonResponse)($issue);
    }
}
