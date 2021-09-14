<?php

declare(strict_types=1);

namespace Workana\Tests\Infrastructure\Delivery\Action\V1\Issue;

use JsonException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Workana\Domain\Model\Issue\Exception\FailIssueUpdateException;
use Workana\Domain\Model\Issue\Exception\InvalidMemberException;
use Workana\Domain\Model\Issue\Exception\InvalidVoteValueException;
use Workana\Domain\Model\Issue\Exception\IssueNotFoundException;
use Workana\Domain\Model\Issue\Exception\IssueNotVotingException;
use Workana\Domain\Model\Issue\Exception\MemberAlreadyVotedOrPassedIssueException;
use Workana\Domain\Model\Issue\Exception\MemberNotJoinedToIssueException;
use Workana\Tests\Domain\Model\Issue\IssueMother;

class VoteIssueActionTest extends IssueServiceActionTest
{
    /**
     * @throws FailIssueUpdateException
     * @throws InvalidMemberException
     * @throws InvalidVoteValueException
     * @throws IssueNotFoundException
     * @throws IssueNotVotingException
     * @throws JsonException
     * @throws MemberAlreadyVotedOrPassedIssueException
     * @throws MemberNotJoinedToIssueException
     * @throws ReflectionException
     */
    public function testFailVoteIssueWhenValueIsNotSent(): void
    {
        $issueMother = IssueMother::reveal();
        $payload = ["name" => "John"];

        $this->expectException(InvalidVoteValueException::class);

        $this->payloadRequestParserService->method('__invoke')
            ->willReturn($payload);

        $this->voteIssueService->method('__invoke')->willReturn($issueMother);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        ($this->voteIssueAction)(1, $request);
    }

    /**
     * @throws JsonException
     * @throws ReflectionException
     * @throws FailIssueUpdateException
     * @throws InvalidMemberException
     * @throws InvalidVoteValueException
     * @throws IssueNotFoundException
     * @throws IssueNotVotingException
     * @throws MemberAlreadyVotedOrPassedIssueException
     * @throws MemberNotJoinedToIssueException
     */
    public function testFailVoteIssueWhenNameIsNotSent(): void
    {
        $issueMother = IssueMother::reveal();
        $payload = ["vote" => 15];

        $this->expectException(InvalidMemberException::class);

        $this->payloadRequestParserService->method('__invoke')
            ->willReturn($payload);

        $this->voteIssueService->method('__invoke')->willReturn($issueMother);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        ($this->voteIssueAction)(1, $request);
    }
}
