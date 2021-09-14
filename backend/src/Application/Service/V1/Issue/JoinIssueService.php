<?php

declare(strict_types=1);

namespace Workana\Application\Service\V1\Issue;

use Workana\Domain\Model\Issue\Exception\FailIssueUpdateException;
use Workana\Domain\Model\Issue\Exception\IssueNotVotingException;
use Workana\Domain\Model\Issue\Exception\MemberAlreadyJoinedException;
use Workana\Domain\Model\Issue\Issue;
use Workana\Domain\Model\Issue\IssueRepositoryInterface;

final class JoinIssueService
{
    private IssueRepositoryInterface $repository;

    public function __construct(IssueRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws MemberAlreadyJoinedException
     * @throws FailIssueUpdateException
     * @throws IssueNotVotingException
     */
    public function __invoke(int $issueId, Issue $issue, string $username): void
    {
        $this->validation($issue, $username, $issueId);
        $this->repository->update($issueId, $issue);
    }

    /**
     * @throws MemberAlreadyJoinedException
     * @throws IssueNotVotingException
     */
    private function validation(Issue $issue, string $username, int $issueId): void
    {
        if (false === $issue->addMember($username)) {
            throw new MemberAlreadyJoinedException($username, $issueId);
        }

        if (Issue::REVEAL === $issue->getStatus()) {
            throw new IssueNotVotingException($issueId);
        }
    }
}
