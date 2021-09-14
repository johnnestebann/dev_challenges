<?php

declare(strict_types=1);

namespace Workana\Tests\Infrastructure\Delivery\Action\V1\Issue;

use JetBrains\PhpStorm\NoReturn;
use JsonException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Workana\Tests\Domain\Model\Issue\IssueMother;

class JoinOrCreateIssueActionTest extends IssueServiceActionTest
{
    /**
     * @throws JsonException
     * @throws ReflectionException
     */
    #[NoReturn]
    public function testCreateIssueWhenNotExistsWithValidMember(): void
    {
        $issueMother = IssueMother::voting();
        $payload = ["name" => "Esteban"];

        $this->payloadRequestParserService->method('__invoke')
            ->willReturn($payload);

        $this->getIssueByIdService->method('__invoke')->willReturn($issueMother);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        $jsonResponse = ($this->joinOrCreateIssueAction)(2, $request);

        $response = json_decode($jsonResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertNotEmpty($response['data']['members']['Esteban']);
        $this->assertEquals('waiting', $response['data']['members']['Esteban']['status']);
        $this->assertEquals(0, $response['data']['members']['Esteban']['value']);
    }

    /**
     * @throws JsonException
     * @throws ReflectionException
     */
    #[NoReturn]
    public function testNotCreateOrJoinIssueWithInvalidMember(): void
    {
        $issueMother = IssueMother::voting();
        $payload = [];

        $this->payloadRequestParserService->method('__invoke')
            ->willReturn($payload);

        $this->getIssueByIdService->method('__invoke')->willReturn($issueMother);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        $jsonResponse = ($this->joinOrCreateIssueAction)(2, $request);

        $response = json_decode($jsonResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals('ERROR', $response['status']);
        $this->assertEquals('Invalid member.', $response['message']);
    }

    /**
     * @throws JsonException
     * @throws ReflectionException
     */
    #[NoReturn]
    public function testNotCreateOrJoinIssueWithAlreadyJoinedMember(): void
    {
        $issueMother = IssueMother::voting();
        $payload = ["name" => "John"];

        $this->payloadRequestParserService->method('__invoke')
            ->willReturn($payload);

        $this->getIssueByIdService->method('__invoke')->willReturn($issueMother);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        $jsonResponse = ($this->joinOrCreateIssueAction)(1, $request);

        $response = json_decode($jsonResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals('ERROR', $response['status']);
        $this->assertEquals('Member \'John\' already joined to Issue with ID \'1\'.', $response['message']);
    }

    /**
     * @throws JsonException
     * @throws ReflectionException
     */
    #[NoReturn]
    public function testNotCreateOrJoinNewMemberOnRevealedIssue(): void
    {
        $issueMother = IssueMother::reveal();
        $payload = ["name" => "Esteban"];

        $this->payloadRequestParserService->method('__invoke')
            ->willReturn($payload);

        $this->getIssueByIdService->method('__invoke')->willReturn($issueMother);

        $request = new Request(content: json_encode($payload, JSON_THROW_ON_ERROR));
        $jsonResponse = ($this->joinOrCreateIssueAction)(1, $request);

        $response = json_decode($jsonResponse->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals('ERROR', $response['status']);
        $this->assertEquals('Issue with ID \'1\' is not voting.', $response['message']);
    }
}
