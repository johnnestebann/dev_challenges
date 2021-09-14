<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Delivery\Action\V1\Issue;

use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Workana\Application\Service\V1\Issue\VoteIssueService;
use Workana\Domain\Model\Issue\Exception\FailIssueUpdateException;
use Workana\Domain\Model\Issue\Exception\InvalidMemberException;
use Workana\Domain\Model\Issue\Exception\InvalidVoteValueException;
use Workana\Domain\Model\Issue\Exception\IssueNotFoundException;
use Workana\Domain\Model\Issue\Exception\IssueNotVotingException;
use Workana\Domain\Model\Issue\Exception\MemberAlreadyVotedOrPassedIssueException;
use Workana\Domain\Model\Issue\Exception\MemberNotJoinedToIssueException;
use Workana\Infrastructure\Delivery\Request\PayloadRequestParserService;
use Workana\Infrastructure\Delivery\Response\Json\V1\Issue\IssueJsonResponse;

final class VoteIssueAction
{
    private PayloadRequestParserService $payloadRequestParserService;

    private VoteIssueService $voteIssueService;

    private IssueJsonResponse $issueJsonResponse;

    public function __construct(
        PayloadRequestParserService $payloadRequestParserService,
        VoteIssueService $voteIssueService,
        IssueJsonResponse $issueJsonResponse
    ) {
        $this->payloadRequestParserService = $payloadRequestParserService;
        $this->voteIssueService = $voteIssueService;
        $this->issueJsonResponse = $issueJsonResponse;
    }

    /**
     * @throws IssueNotFoundException
     * @throws MemberNotJoinedToIssueException
     * @throws JsonException
     * @throws IssueNotVotingException
     * @throws InvalidMemberException
     * @throws InvalidVoteValueException
     * @throws FailIssueUpdateException
     * @throws MemberAlreadyVotedOrPassedIssueException
     */
    public function __invoke(int $issueId, Request $request): JsonResponse
    {
        $data = ($this->payloadRequestParserService)((string)$request->getContent());

        if (empty($data) || empty($data['vote'])) {
            throw new InvalidVoteValueException();
        }

        if (empty($data['name'])) {
            throw new InvalidMemberException();
        }

        $issue = ($this->voteIssueService)($issueId, $data['name'], $data['vote']);

        return ($this->issueJsonResponse)($issue);
    }
}
