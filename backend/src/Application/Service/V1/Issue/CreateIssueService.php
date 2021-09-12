<?php

declare(strict_types=1);

namespace Workana\Application\Service\V1\Issue;

use Workana\Domain\Model\Issue\Exception\FailIssueCreationException;
use Workana\Domain\Model\Issue\Issue;
use Workana\Domain\Model\Issue\IssueRepositoryInterface;

final class CreateIssueService
{
	private IssueRepositoryInterface $repository;

	public function __construct(IssueRepositoryInterface $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * @throws FailIssueCreationException
	 */
	public function __invoke(int $issueId): Issue
	{
		return $this->repository->create($issueId);
	}
}