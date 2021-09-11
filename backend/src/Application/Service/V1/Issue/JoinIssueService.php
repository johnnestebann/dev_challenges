<?php

declare(strict_types=1);

namespace Workana\Application\Service\V1\Issue;

use Psr\Cache\InvalidArgumentException;
use Workana\Domain\Model\Issue\IssueRepositoryInterface;

final class JoinIssueService
{
	private IssueRepositoryInterface $repository;

	public function __construct(IssueRepositoryInterface $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function __invoke(int $issueId)
	{
		$this->repository->findById($issueId);
	}
}