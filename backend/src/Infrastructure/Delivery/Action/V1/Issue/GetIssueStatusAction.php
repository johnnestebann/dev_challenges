<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Delivery\Action\V1\Issue;

use Symfony\Component\HttpFoundation\JsonResponse;
use Workana\Application\Service\V1\Issue\GetIssueByIdService;
use Workana\Domain\Model\Issue\Exception\IssueNotFoundException;
use Workana\Infrastructure\Delivery\Response\Json\V1\Issue\IssueJsonResponse;

final class GetIssueStatusAction
{
    private GetIssueByIdService $getIssueByIdService;

    private IssueJsonResponse $issueJsonResponse;

    public function __construct(
        GetIssueByIdService $getIssueByIdService,
        IssueJsonResponse $issueJsonResponse
    ) {
        $this->getIssueByIdService = $getIssueByIdService;
        $this->issueJsonResponse = $issueJsonResponse;
    }

    /**
     * @throws IssueNotFoundException
     */
    public function __invoke(int $issueId): JsonResponse
    {
        $issue = ($this->getIssueByIdService)($issueId);

        return ($this->issueJsonResponse)($issue);
    }
}
