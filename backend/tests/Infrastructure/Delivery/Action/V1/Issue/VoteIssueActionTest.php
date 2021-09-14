<?php

declare(strict_types=1);

namespace Workana\Tests\Infrastructure\Delivery\Action\V1\Issue;

use JsonException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Workana\Tests\Domain\Model\Issue\IssueMother;

class VoteIssueActionTest extends IssueServiceActionTest
{
    /**
     * @throws ReflectionException
     * @throws JsonException
     */
    public function testFailVoteIssueWhenValueIsNotSent(): void
    {
        $issueMother = IssueMother::reveal();
        $payload = ["name" => "John"];

        $this->payloadRequestParserService->method('__invoke')
            ->willReturn($payload);

        $this->voteIssueService->method('__invoke')->willReturn($issueMother);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        $jsonResponse = ($this->voteIssueAction)(1, $request);

        $response = json_decode($jsonResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals('ERROR', $response['status']);
        $this->assertEquals('Invalid vote value.', $response['message']);
    }

    /**
     * @throws ReflectionException
     * @throws JsonException
     */
    public function testFailVoteIssueWhenNameIsNotSent(): void
    {
        $issueMother = IssueMother::reveal();
        $payload = ["vote" => 15];

        $this->payloadRequestParserService->method('__invoke')
            ->willReturn($payload);

        $this->voteIssueService->method('__invoke')->willReturn($issueMother);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        $jsonResponse = ($this->voteIssueAction)(1, $request);

        $response = json_decode($jsonResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals('ERROR', $response['status']);
        $this->assertEquals('Invalid member.', $response['message']);
    }
}
