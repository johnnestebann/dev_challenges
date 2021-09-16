<?php

declare(strict_types=1);

namespace Workana\Tests\Infrastructure\Delivery\Action\V1\Issue;

use JsonException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Workana\Domain\Model\Issue\Exception\FailIssueCreationException;
use Workana\Domain\Model\Issue\Exception\FailIssueUpdateException;
use Workana\Domain\Model\Issue\Exception\InvalidMemberException;
use Workana\Domain\Model\Issue\Exception\IssueNotVotingException;
use Workana\Domain\Model\Issue\Exception\MemberAlreadyJoinedException;
use Workana\Tests\Domain\Model\Issue\IssueMother;

class JoinOrCreateIssueActionTest extends IssueServiceActionTest
{
    /**
     * @throws JsonException
     * @throws ReflectionException
     * @throws FailIssueCreationException
     * @throws FailIssueUpdateException
     * @throws InvalidMemberException
     * @throws IssueNotVotingException
     * @throws MemberAlreadyJoinedException
     */
    public function testCreateIssueWhenNotExistsWithValidMember(): void
    {
        $issueMother = IssueMother::voting();
        $payload = ["name" => "Esteban"];

        $this->issueRepository->create(1, $issueMother->getStatus(), $issueMother->toFullArray()['members'], $issueMother->getAvg());

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        $jsonResponse = ($this->joinOrCreateIssueAction)(1, $request);

        $response = json_decode((string) $jsonResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertNotEmpty($response['data']['members']['Esteban']);
        $this->assertEquals('waiting', $response['data']['members']['Esteban']['status']);
        $this->assertEquals(0, $response['data']['members']['Esteban']['value']);
    }

    /**
     * @throws FailIssueCreationException
     * @throws FailIssueUpdateException
     * @throws InvalidMemberException
     * @throws IssueNotVotingException
     * @throws JsonException
     * @throws MemberAlreadyJoinedException
     * @throws ReflectionException
     */
    public function testNotCreateOrJoinIssueWithInvalidMember(): void
    {
        $issueMother = IssueMother::voting();
        $payload = [];

        $this->issueRepository->create(1, $issueMother->getStatus(), $issueMother->toFullArray()['members'], $issueMother->getAvg());

        $this->expectException(InvalidMemberException::class);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        ($this->joinOrCreateIssueAction)(2, $request);
    }

    /**
     * @throws FailIssueCreationException
     * @throws FailIssueUpdateException
     * @throws InvalidMemberException
     * @throws IssueNotVotingException
     * @throws JsonException
     * @throws MemberAlreadyJoinedException
     * @throws ReflectionException
     */
    public function testNotCreateOrJoinIssueWithAlreadyJoinedMember(): void
    {
        $issueMother = IssueMother::voting();
        $payload = ["name" => "John"];

        $this->issueRepository->create(1, $issueMother->getStatus(), $issueMother->toFullArray()['members'], $issueMother->getAvg());

        $this->expectException(MemberAlreadyJoinedException::class);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        ($this->joinOrCreateIssueAction)(1, $request);
    }

    /**
     * @throws FailIssueCreationException
     * @throws FailIssueUpdateException
     * @throws InvalidMemberException
     * @throws IssueNotVotingException
     * @throws JsonException
     * @throws MemberAlreadyJoinedException
     * @throws ReflectionException
     */
    public function testNotCreateOrJoinNewMemberOnRevealedIssue(): void
    {
        $issueMother = IssueMother::reveal();
        $payload = ["name" => "Esteban"];

        $this->issueRepository->create(1, $issueMother->getStatus(), $issueMother->toFullArray()['members'], $issueMother->getAvg());

        $this->expectException(IssueNotVotingException::class);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        ($this->joinOrCreateIssueAction)(1, $request);
    }
}
