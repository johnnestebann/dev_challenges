<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Delivery\Action\V1\Issue;

use Exception;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Workana\Application\Service\V1\Issue\CreateIssueService;
use Workana\Application\Service\V1\Issue\GetIssueByIdService;
use Workana\Application\Service\V1\Issue\JoinIssueService;
use Workana\Domain\Model\Issue\Exception\FailIssueCreationException;
use Workana\Domain\Model\Issue\Exception\FailIssueUpdateException;
use Workana\Domain\Model\Issue\Exception\InvalidMemberException;
use Workana\Domain\Model\Issue\Exception\IssueNotVotingException;
use Workana\Domain\Model\Issue\Exception\MemberAlreadyJoinedException;
use Workana\Infrastructure\Delivery\Request\PayloadRequestParserService;
use Workana\Infrastructure\Delivery\Response\Json\V1\Issue\IssueJsonResponse;

final class JoinOrCreateIssueAction
{
    private GetIssueByIdService $getIssueByIdService;

    private CreateIssueService $createIssueService;

    private PayloadRequestParserService $payloadRequestParserService;

    private JoinIssueService $joinIssueService;

    private IssueJsonResponse $issueJsonResponse;

    public function __construct(
        GetIssueByIdService $getIssueByIdService,
        CreateIssueService $createIssueService,
        PayloadRequestParserService $payloadRequestParserService,
        JoinIssueService $joinIssueService,
        IssueJsonResponse $issueJsonResponse
    ) {
        $this->getIssueByIdService = $getIssueByIdService;
        $this->createIssueService = $createIssueService;
        $this->payloadRequestParserService = $payloadRequestParserService;
        $this->joinIssueService = $joinIssueService;
        $this->issueJsonResponse = $issueJsonResponse;
    }

    /**
     * @throws IssueNotVotingException
     * @throws InvalidMemberException
     * @throws FailIssueCreationException
     * @throws MemberAlreadyJoinedException
     * @throws FailIssueUpdateException
     * @throws JsonException
     */
    public function __invoke(int $issueId, Request $request): JsonResponse
    {
        $data = ($this->payloadRequestParserService)((string)$request->getContent());

        if (empty($data) || empty($data['name'])) {
            throw new InvalidMemberException();
        }

        try {
            $issue = ($this->getIssueByIdService)($issueId);
        } catch (Exception) {
            $issue = ($this->createIssueService)($issueId);
        }

        ($this->joinIssueService)($issueId, $issue, $data['name']);

        return ($this->issueJsonResponse)($issue);
    }
}
