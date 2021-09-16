<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Persistence\Redis\Repository;

use Exception;
use Redis;
use Workana\Domain\Model\Issue\Exception\FailIssueCreationException;
use Workana\Domain\Model\Issue\Exception\FailIssueUpdateException;
use Workana\Domain\Model\Issue\Exception\IssueNotFoundException;
use Workana\Domain\Model\Issue\Issue;
use Workana\Domain\Model\Issue\IssueRepositoryInterface;
use Workana\Infrastructure\Persistence\Redis\Service\RedisConnectionService;

final class RedisIssueRepository implements IssueRepositoryInterface
{
    private Redis $cache;

    public function __construct(RedisConnectionService $cache)
    {
        $this->cache = ($cache)();
    }

    /**
     * @throws FailIssueCreationException
     */
    public function create(int $issueId, string $status = 'voting', array $members = [], int $avg = 0): Issue
    {
        try {
            $issue = Issue::create($status, $members, $avg);

            $key = 'issue#' . $issueId;
            $value = json_encode($issue->toFullArray(), JSON_THROW_ON_ERROR);

            if (false === $this->cache->set($key, $value)) {
                throw new Exception();
            }

            return $issue;
        } catch (Exception) {
            throw new FailIssueCreationException();
        }
    }

    /**
     * @throws IssueNotFoundException
     */
    public function findById(int $issueId): Issue
    {
        try {
            $key = 'issue#' . $issueId;
            $value = $this->cache->get($key);

            if (empty($value)) {
                throw new Exception();
            }

            $data = json_decode($value, true, 512, JSON_THROW_ON_ERROR);

            return Issue::create(
                $data['status'] ?? '',
                $data['members'] ?? [],
                $data['avg'] ?? 0
            );
        } catch (Exception) {
            throw new IssueNotFoundException($issueId);
        }
    }

    /**
     * @throws FailIssueUpdateException
     */
    public function update(int $issueId, Issue $issue): void
    {
        try {
            $key = 'issue#' . $issueId;
            $value = json_encode($issue->toFullArray(), JSON_THROW_ON_ERROR);

            if (false === $this->cache->set($key, $value)) {
                throw new Exception();
            }
        } catch (Exception) {
            throw new FailIssueUpdateException();
        }
    }
}
