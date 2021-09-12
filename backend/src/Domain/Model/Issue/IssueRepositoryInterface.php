<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue;

use JsonException;

interface IssueRepositoryInterface
{
	/**
	 * @param int $issueId
	 * @return Issue|null
	 * @throws JsonException
	 */
	public function create(int $issueId): ?Issue;

	/**
	 * @param int $issueId
	 * @return Issue|null
	 * @throws JsonException
	 *
	 */
	public function findById(int $issueId): ?Issue;

	/**
	 * @param int $issueId
	 * @param Issue $issue
	 * @throws JsonException
	 */
	public function update(int $issueId, Issue $issue): void;
}