<?php

declare(strict_types=1);

namespace Workana\Application\Service\V1\Issue;

use JsonException;
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
	 * @throws JsonException
	 */
	public function __invoke(int $issueId, Issue $issue, string $username): void
	{
		if (false === $issue->joinMember($username)) {
			throw new MemberAlreadyJoinedException($username, $issueId);
		}

		$this->repository->update($issueId, $issue);
	}
}