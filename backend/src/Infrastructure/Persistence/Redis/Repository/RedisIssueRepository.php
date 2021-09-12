<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Persistence\Redis\Repository;

use Workana\Domain\Model\Issue\Issue;
use Workana\Domain\Model\Issue\IssueRepositoryInterface;
use Workana\Infrastructure\Persistence\Redis\Service\RedisConnectionService;
use JsonException;
use Redis;

final class RedisIssueRepository implements IssueRepositoryInterface
{
	private Redis $cache;

	public function __construct(
		RedisConnectionService $cache
	)
	{
		$this->cache = ($cache)();
	}

	/**
	 * @throws JsonException
	 */
	public function create(int $issueId): ?Issue
	{
		$key = 'issue#' . $issueId;

		$issue = Issue::create();

		$this->cache->set($key, json_encode($issue->toArray(), JSON_THROW_ON_ERROR));

		return $issue;
	}

	/**
	 * @throws JsonException
	 */
	public function findById(int $issueId): ?Issue
	{
		$key = 'issue#' . $issueId;
		$value = $this->cache->get($key);

		if (empty($value)) {
			return null;
		}

		$data = json_decode($this->cache->get($key), true, 512, JSON_THROW_ON_ERROR);

		return Issue::create(
			$data['status'] ?? '',
			$data['members'] ?? [],
			$data['avg'] ?? 0
		);
	}

	/**
	 * @throws JsonException
	 */
	public function update(int $issueId, Issue $issue): void
	{
		$key = 'issue#' . $issueId;

		$value = json_encode($issue->toArray(), JSON_THROW_ON_ERROR);

		$this->cache->set($key, $value);
	}
}