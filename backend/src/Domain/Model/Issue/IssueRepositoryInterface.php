<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue;

use Psr\Cache\InvalidArgumentException;

interface IssueRepositoryInterface
{
	public function create(int $issueId): ?Issue;

	/**
	 * @param int $issueId
	 * @return Issue|null
	 * @throws InvalidArgumentException
	 *
	 */
	public function findById(int $issueId): ?Issue;


}