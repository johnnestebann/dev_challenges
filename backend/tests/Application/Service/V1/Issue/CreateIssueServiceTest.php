<?php

declare(strict_types=1);

namespace Workana\Tests\Application\Service\V1\Issue;

use ReflectionException;
use Workana\Application\Service\V1\Issue\CreateIssueService;
use Workana\Domain\Model\Issue\Exception\FailIssueCreationException;
use Workana\Tests\Domain\Model\Issue\IssueMother;

class CreateIssueServiceTest extends IssueServiceTest
{
	/**
	 * @throws ReflectionException
	 * @throws FailIssueCreationException
	 */
	public function testCreateValidIssue(): void
	{
		$issueMother = IssueMother::voting();
		$this->issueRepository->method('create')->willReturn($issueMother);

		$createIssueService = new CreateIssueService($this->issueRepository);
		$issue = ($createIssueService)(1);

		$this->assertEquals($issueMother, $issue);
		$this->assertSame($issueMother->getStatus(), $issue->getStatus());
		$this->assertSame($issueMother->getMembers(), $issue->getMembers());
		$this->assertSame($issueMother->getAvg(), $issue->getAvg());
	}
}