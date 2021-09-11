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

	public function save(Issue $issue): void
	{

	}

	/**
	 * @throws JsonException
	 */
	public function findById(int $id): ?Issue
	{
		$key = 'issue#' . $id;

		if (false === $this->cache->get($key)) {
			return null;
		}

		$data = json_decode($this->cache->get($key), true, 512, JSON_THROW_ON_ERROR);

		return Issue::create(
			$data['status'] ?? '',
			$data['members'] ?? [],
			$data['avg'] ?? 0
		);
	}
}