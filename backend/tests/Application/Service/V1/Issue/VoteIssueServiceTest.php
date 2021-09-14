<?php

declare(strict_types=1);

namespace Workana\Tests\Application\Service\V1\Issue;

use ReflectionException;
use Workana\Application\Service\V1\Issue\VoteIssueService;
use Workana\Domain\Model\Issue\Exception\FailIssueUpdateException;
use Workana\Domain\Model\Issue\Exception\IssueNotFoundException;
use Workana\Domain\Model\Issue\Exception\IssueNotVotingException;
use Workana\Domain\Model\Issue\Exception\MemberAlreadyVotedOrPassedIssueException;
use Workana\Domain\Model\Issue\Exception\MemberNotJoinedToIssueException;
use Workana\Tests\Domain\Model\Issue\IssueMother;

class VoteIssueServiceTest extends IssueServiceTest
{
    /**
     * @throws ReflectionException
     * @throws FailIssueUpdateException
     * @throws IssueNotFoundException
     * @throws IssueNotVotingException
     * @throws MemberAlreadyVotedOrPassedIssueException
     * @throws MemberNotJoinedToIssueException
     */
    public function testValidMemberVoteValidIssue(): void
    {
        $issueMother = IssueMother::voting();
        $username = 'John';

        $this->getIssueByIdService->method('__invoke')->willReturn($issueMother);
        $this->issueRepository->method('update');

        $issue = ($this->voteIssueService)(1, $username, 10);

        $this->assertEquals($issueMother, $issue);
        $this->assertSame($issueMother->getStatus(), $issue->getStatus());
        $this->assertSame($issueMother->getMembers(), $issue->getMembers());
        $this->assertSame($issueMother->getAvg(), $issue->getAvg());
    }

    /**
     * @throws ReflectionException
     * @throws FailIssueUpdateException
     * @throws IssueNotFoundException
     * @throws IssueNotVotingException
     * @throws MemberAlreadyVotedOrPassedIssueException
     * @throws MemberNotJoinedToIssueException
     */
    public function testFailMemberNotJoinedTryingToVoteIssue(): void
    {
        $issueMother = IssueMother::voting();
        $username = 'Peter';

        $this->expectException(MemberNotJoinedToIssueException::class);

        $this->issueRepository->method('update');
        $this->getIssueByIdService->method('__invoke')->willReturn($issueMother);

        $voteIssueService = new VoteIssueService($this->getIssueByIdService, $this->issueRepository);
        ($voteIssueService)(1, $username, 10);
    }

    /**
     * @throws ReflectionException
     * @throws FailIssueUpdateException
     * @throws IssueNotFoundException
     * @throws IssueNotVotingException
     * @throws MemberAlreadyVotedOrPassedIssueException
     * @throws MemberNotJoinedToIssueException
     */
    public function testFailValidMemberTryingToVoteIssueAgain(): void
    {
        $issueMother = IssueMother::voting();
        $username = 'Pia';

        $this->expectException(MemberAlreadyVotedOrPassedIssueException::class);

        $this->issueRepository->method('update');
        $this->getIssueByIdService->method('__invoke')->willReturn($issueMother);

        $voteIssueService = new VoteIssueService($this->getIssueByIdService, $this->issueRepository);
        ($voteIssueService)(1, $username, 10);
    }

    /**
     * @throws ReflectionException
     * @throws FailIssueUpdateException
     * @throws IssueNotFoundException
     * @throws IssueNotVotingException
     * @throws MemberAlreadyVotedOrPassedIssueException
     * @throws MemberNotJoinedToIssueException
     */
    public function testFailValidMemberTryingToVoteRevealedIssue(): void
    {
        $issueMother = IssueMother::reveal();
        $username = 'Pia';

        $this->expectException(IssueNotVotingException::class);

        $this->issueRepository->method('update');
        $this->getIssueByIdService->method('__invoke')->willReturn($issueMother);

        $voteIssueService = new VoteIssueService($this->getIssueByIdService, $this->issueRepository);
        ($voteIssueService)(1, $username, 10);
    }
}
