<?php

declare(strict_types=1);

namespace Workana\Application\Service\V1\Issue;

use JsonException;
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
	 * @throws JsonException
	 */
	public function __invoke(int $issueId): Issue
	{
		return $this->repository->create($issueId);
	}
}