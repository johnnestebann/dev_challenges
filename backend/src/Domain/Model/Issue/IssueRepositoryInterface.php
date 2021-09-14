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
     * @return Issue
     * @throws FailIssueCreationException
     */
    public function create(int $issueId): Issue;

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
