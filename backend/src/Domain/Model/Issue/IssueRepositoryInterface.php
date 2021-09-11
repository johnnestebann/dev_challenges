<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue;

use Psr\Cache\InvalidArgumentException;

interface IssueRepositoryInterface
{
	public function save(Issue $issue): void;

	/**
	 * @param int $id
	 * @return Issue|null
	 * @throws InvalidArgumentException
	 *
	 */
	public function findById(int $id): ?Issue;
}