<?php

declare(strict_types=1);

namespace Workana\Tests\Application\Service\V1\Issue;

use ReflectionException;
use Workana\Application\Service\V1\Issue\JoinIssueService;
use Workana\Domain\Model\Issue\Exception\FailIssueUpdateException;
use Workana\Domain\Model\Issue\Exception\IssueNotVotingException;
use Workana\Domain\Model\Issue\Exception\MemberAlreadyJoinedException;
use Workana\Tests\Domain\Model\Issue\IssueMother;

class JoinIssueServiceTest extends IssueServiceTest
{
	/**
	 * @throws ReflectionException
	 * @throws FailIssueUpdateException
	 * @throws MemberAlreadyJoinedException
	 * @throws IssueNotVotingException
	 */
	public function testJoinValidMemberToValidIssue(): void
	{
		$issue = IssueMother::voting();
		$username = 'Peter';

		$this->issueRepository->method('update');

		$getIssueByIdService = new JoinIssueService($this->issueRepository);
		($getIssueByIdService)(1, $issue, $username);

		$this->assertNotEmpty($issue->getMembers()['Peter']);
		$this->assertSame('waiting', $issue->getMembers()['Peter']['status']);
		$this->assertSame(0, $issue->getMembers()['Peter']['value']);
		$this->assertSame($issue->getAvg(), $issue->getAvg());
	}

	/**
	 * @throws ReflectionException
	 * @throws FailIssueUpdateException
	 * @throws MemberAlreadyJoinedException
	 * @throws IssueNotVotingException
	 */
	public function testFailJoinMemberAlreadyJoinedToValidIssue(): void
	{
		$issue = IssueMother::voting();
		$username = 'John';

		$this->expectException(MemberAlreadyJoinedException::class);

		$this->issueRepository->method('update');

		$getIssueByIdService = new JoinIssueService($this->issueRepository);
		($getIssueByIdService)(1, $issue, $username);
	}

	/**
	 * @throws ReflectionException
	 * @throws FailIssueUpdateException
	 * @throws IssueNotVotingException
	 * @throws MemberAlreadyJoinedException
	 */
	public function testFailJoinValidMemberToValidIssueRevealed(): void
	{
		$issue = IssueMother::reveal();
		$username = 'Esteban';

		$this->expectException(IssueNotVotingException::class);

		$this->issueRepository->method('update');

		$getIssueByIdService = new JoinIssueService($this->issueRepository);
		($getIssueByIdService)(1, $issue, $username);
	}
}