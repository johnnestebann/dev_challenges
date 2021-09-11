<?php

declare(strict_types=1);

namespace Workana\Application\Service\V1\Issue;

use Workana\Domain\Model\Issue\Issue;
use Workana\Domain\Model\Issue\IssueRepositoryInterface;
use Psr\Cache\InvalidArgumentException;

final class GetIssueByIdService
{
	private IssueRepositoryInterface $repository;

	public function __construct(IssueRepositoryInterface $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function __invoke(int $issueId): ?Issue
	{
		return $this->repository->findById($issueId);
	}
}