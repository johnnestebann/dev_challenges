<?php

declare(strict_types=1);

namespace Workana\Tests\Infrastructure\Delivery\Action\V1\Issue;

use JsonException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Workana\Domain\Model\Issue\Exception\FailIssueCreationException;
use Workana\Domain\Model\Issue\Exception\FailIssueUpdateException;
use Workana\Domain\Model\Issue\Exception\InvalidMemberException;
use Workana\Domain\Model\Issue\Exception\InvalidVoteValueException;
use Workana\Domain\Model\Issue\Exception\IssueNotFoundException;
use Workana\Domain\Model\Issue\Exception\IssueNotVotingException;
use Workana\Domain\Model\Issue\Exception\MemberAlreadyVotedOrPassedIssueException;
use Workana\Domain\Model\Issue\Exception\MemberNotJoinedToIssueException;
use Workana\Domain\Model\Issue\Issue;
use Workana\Tests\Domain\Model\Issue\IssueMother;

class VoteIssueActionTest extends IssueServiceActionTest
{
    /**
     * @throws IssueNotFoundException
     * @throws JsonException
     * @throws MemberNotJoinedToIssueException
     * @throws InvalidMemberException
     * @throws IssueNotVotingException
     * @throws ReflectionException
     * @throws FailIssueUpdateException
     * @throws MemberAlreadyVotedOrPassedIssueException
     * @throws FailIssueCreationException
     * @throws InvalidVoteValueException
     */
    public function testValidVoteOnValidIssue(): void
    {
        $issueMother = IssueMother::voting();
        $payload = ["name" => "John", "vote" => 5];

        $this->issueRepository->create(1, $issueMother->getStatus(), $issueMother->toFullArray()['members'], $issueMother->getAvg());

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        $jsonResponse = ($this->voteIssueAction)(1, $request);
        $response = json_decode((string) $jsonResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals(Issue::REVEAL, $response['data']['status']);
        $this->assertEquals(5, $response['data']['members']['John']['value']);
        $this->assertEquals(10, $response['data']['avg']);
    }

    /**
     * @throws FailIssueUpdateException
     * @throws InvalidMemberException
     * @throws InvalidVoteValueException
     * @throws IssueNotFoundException
     * @throws IssueNotVotingException
     * @throws JsonException
     * @throws MemberAlreadyVotedOrPassedIssueException
     * @throws MemberNotJoinedToIssueException
     * @throws FailIssueCreationException
     * @throws ReflectionException
     */
    public function testFailVoteIssueWhenValueIsNotSent(): void
    {
        $issueMother = IssueMother::voting();
        $payload = ["name" => "John"];

        $this->issueRepository->create(1, $issueMother->getStatus(), $issueMother->toFullArray()['members'], $issueMother->getAvg());

        $this->expectException(InvalidVoteValueException::class);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        ($this->voteIssueAction)(1, $request);
    }

    /**
     * @throws JsonException
     * @throws FailIssueUpdateException
     * @throws InvalidMemberException
     * @throws InvalidVoteValueException
     * @throws IssueNotFoundException
     * @throws IssueNotVotingException
     * @throws MemberAlreadyVotedOrPassedIssueException
     * @throws MemberNotJoinedToIssueException
     * @throws FailIssueCreationException
     * @throws ReflectionException
     */
    public function testFailVoteIssueWhenNameIsNotSent(): void
    {
        $issueMother = IssueMother::voting();
        $payload = ["vote" => 15];

        $this->issueRepository->create(1, $issueMother->getStatus(), $issueMother->toFullArray()['members'], $issueMother->getAvg());

        $this->expectException(InvalidMemberException::class);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        ($this->voteIssueAction)(1, $request);
    }
}
