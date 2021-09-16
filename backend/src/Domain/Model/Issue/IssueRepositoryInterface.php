<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue;

use Workana\Domain\Model\Issue\Exception\FailIssueCreationException;
use Workana\Domain\Model\Issue\Exception\FailIssueUpdateException;
use Workana\Domain\Model\Issue\Exception\IssueNotFoundException;

interface IssueRepositoryInterface
{
    /**
     * @param int $issueId
     * @param string $status
     * @param array $members
     * @param int $avg
     * @return Issue
     * @throws FailIssueCreationException
     */
    public function create(int $issueId, string $status = 'voting', array $members = [], int $avg = 0): Issue;

    /**
     * @param int $issueId
     * @return Issue
     * @throws IssueNotFoundException
     */
    public function findById(int $issueId): Issue;

    /**
     * @param int $issueId
     * @param Issue $issue
     * @throws FailIssueUpdateException
     */
    public function update(int $issueId, Issue $issue): void;
}
