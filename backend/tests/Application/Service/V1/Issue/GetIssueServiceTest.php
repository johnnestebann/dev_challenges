<?php

namespace Workana\Tests\Application\Service\V1\Issue;

use ReflectionException;
use Workana\Application\Service\V1\Issue\GetIssueByIdService;
use Workana\Domain\Model\Issue\Exception\IssueNotFoundException;
use Workana\Tests\Domain\Model\Issue\IssueMother;

class GetIssueServiceTest extends IssueServiceTest
{
    /**
     * @throws ReflectionException
     * @throws IssueNotFoundException
     */
    public function testGetValidIssue(): void
    {
        $issueMother = IssueMother::voting();
        $this->issueRepository->method('findById')->willReturn($issueMother);

        $getIssueByIdService = new GetIssueByIdService($this->issueRepository);
        $issue = ($getIssueByIdService)(1);

        $this->assertEquals($issueMother, $issue);
        $this->assertSame($issueMother->getStatus(), $issue->getStatus());
        $this->assertSame($issueMother->getMembers(), $issue->getMembers());
        $this->assertSame($issueMother->getAvg(), $issue->getAvg());
    }
}
