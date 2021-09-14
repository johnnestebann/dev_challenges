<?php

namespace Workana\Tests\Infrastructure\Delivery\Action\V1\Issue;

use JsonException;
use ReflectionException;
use Workana\Domain\Model\Issue\Exception\IssueNotFoundException;
use Workana\Tests\Domain\Model\Issue\IssueMother;

class GetIssueStatusActionTest extends IssueServiceActionTest
{
    /**
     * @throws JsonException
     * @throws ReflectionException
     * @throws IssueNotFoundException
     */
    public function testIssueVotingCanNotShowVoteAndAvgValues(): void
    {
        $issueMother = IssueMother::voting();
        $this->issueRepository->method('findById')->willReturn($issueMother);

        $jsonResponse = ($this->getIssueStatusAction)(1);
        $response = json_decode((string) $jsonResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals($response['data']['status'], $issueMother->getStatus());
        $this->assertEquals(0, $response['data']['members']['Pia']['value']);
        $this->assertEquals(0, $response['data']['avg']);
    }

    /**
     * @throws IssueNotFoundException
     * @throws JsonException
     * @throws ReflectionException
     */
    public function testIssueRevealedMustShowVoteAndAvgValues(): void
    {
        $issueMother = IssueMother::reveal();
        $this->issueRepository->method('findById')->willReturn($issueMother);

        $jsonResponse = ($this->getIssueStatusAction)(1);
        $response = json_decode((string) $jsonResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals($issueMother->getStatus(), $response['data']['status']);
        $this->assertEquals($issueMother->getAvg(), $response['data']['avg']);
        $this->assertEquals(7, $response['data']['avg']);
        $this->assertEquals($issueMother->getMembers()['Pia']['value'], $response['data']['members']['Pia']['value']);
    }
}
