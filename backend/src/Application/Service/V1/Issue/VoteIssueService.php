<?php

declare(strict_types=1);

namespace Workana\Application\Service\V1\Issue;

use Workana\Domain\Model\Issue\Exception\FailIssueUpdateException;
use Workana\Domain\Model\Issue\Exception\IssueNotFoundException;
use Workana\Domain\Model\Issue\Exception\IssueNotVotingException;
use Workana\Domain\Model\Issue\Exception\MemberAlreadyVotedOrPassedIssueException;
use Workana\Domain\Model\Issue\Exception\MemberNotJoinedToIssueException;
use Workana\Domain\Model\Issue\Issue;
use Workana\Domain\Model\Issue\IssueRepositoryInterface;

final class VoteIssueService
{
	private GetIssueByIdService $getIssueByIdService;

	private IssueRepositoryInterface $repository;

	public function __construct(
		GetIssueByIdService      $getIssueByIdService,
		IssueRepositoryInterface $repository
	)
	{
		$this->getIssueByIdService = $getIssueByIdService;
		$this->repository = $repository;
	}

	/**
	 * @throws IssueNotFoundException
	 * @throws IssueNotVotingException
	 * @throws MemberNotJoinedToIssueException
	 * @throws MemberAlreadyVotedOrPassedIssueException
	 * @throws FailIssueUpdateException
	 */
	public function __invoke(int $issueId, string $username, int $vote): Issue
	{
		$issue = ($this->getIssueByIdService)($issueId);

		$this->validation($issue, $username, $issueId);

		$issue->memberVote($username, $vote);

		$this->repository->update($issueId, $issue);

		return $issue;
	}

	/**
	 * @throws IssueNotVotingException
	 * @throws MemberNotJoinedToIssueException
	 * @throws MemberAlreadyVotedOrPassedIssueException
	 */
	private function validation(Issue $issue, string $username, int $issueId): void
	{
		if (Issue::REVEAL === $issue->getStatus()) {
			throw new IssueNotVotingException($issueId);
		}

		if (false === $issue->hasMember($username)) {
			throw new MemberNotJoinedToIssueException($username, $issueId);
		}

		if ($issue->memberAlreadyVotedOrPassed($username)) {
			throw new MemberAlreadyVotedOrPassedIssueException($username, $issueId);
		}
	}
}