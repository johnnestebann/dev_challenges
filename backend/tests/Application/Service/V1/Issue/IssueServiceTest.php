<?php

declare(strict_types=1);

namespace Workana\Tests\Application\Service\V1\Issue;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Workana\Application\Service\V1\Issue\GetIssueByIdService;
use Workana\Application\Service\V1\Issue\VoteIssueService;
use Workana\Domain\Model\Issue\IssueRepositoryInterface;

abstract class IssueServiceTest extends TestCase
{
	protected MockObject|IssueRepositoryInterface $issueRepository;

	protected MockObject|GetIssueByIdService $getIssueByIdService;

	protected VoteIssueService $voteIssueService;

	protected function setUp(): void
	{
		$this->issueRepository = $this->createMock(IssueRepositoryInterface::class);
		$this->getIssueByIdService = $this->createMock(GetIssueByIdService::class);

		$this->voteIssueService = new VoteIssueService(
			$this->getIssueByIdService,
			$this->issueRepository
		);
	}
}