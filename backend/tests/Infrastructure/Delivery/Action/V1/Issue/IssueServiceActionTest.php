<?php

namespace Workana\Tests\Infrastructure\Delivery\Action\V1\Issue;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Workana\Application\Service\V1\Issue\CreateIssueService;
use Workana\Application\Service\V1\Issue\GetIssueByIdService;
use Workana\Application\Service\V1\Issue\JoinIssueService;
use Workana\Application\Service\V1\Issue\VoteIssueService;
use Workana\Domain\Model\Issue\IssueRepositoryInterface;
use Workana\Infrastructure\Delivery\Action\V1\Issue\GetIssueStatusAction;
use Workana\Infrastructure\Delivery\Action\V1\Issue\JoinOrCreateIssueAction;
use Workana\Infrastructure\Delivery\Action\V1\Issue\VoteIssueAction;
use Workana\Infrastructure\Delivery\Request\PayloadRequestParserService;
use Workana\Infrastructure\Delivery\Response\Json\V1\Error\ErrorJsonResponse;
use Workana\Infrastructure\Delivery\Response\Json\V1\Issue\IssueJsonResponse;

abstract class IssueServiceActionTest extends TestCase
{
	protected MockObject|IssueRepositoryInterface $issueRepository;

	protected MockObject|GetIssueByIdService $getIssueByIdService;

	protected MockObject|PayloadRequestParserService $payloadRequestParserService;

	protected MockObject|VoteIssueService $voteIssueService;

	protected GetIssueStatusAction $getIssueStatusAction;

	protected JoinOrCreateIssueAction $joinOrCreateIssueAction;

	protected VoteIssueAction $voteIssueAction;

	protected function setUp(): void
	{
		$this->issueRepository = $this->createMock(IssueRepositoryInterface::class);

		$this->payloadRequestParserService = $this->createMock(PayloadRequestParserService::class);
		$this->getIssueByIdService = $this->createMock(GetIssueByIdService::class);
		$this->voteIssueService = $this->createMock(VoteIssueService::class);

		$this->getIssueStatusAction = new GetIssueStatusAction(
			new GetIssueByIdService($this->issueRepository),
			new ErrorJsonResponse(),
			new IssueJsonResponse()
		);

		$this->joinOrCreateIssueAction = new JoinOrCreateIssueAction(
			$this->getIssueByIdService,
			new CreateIssueService($this->issueRepository),
			$this->payloadRequestParserService,
			new JoinIssueService($this->issueRepository),
			new ErrorJsonResponse(),
			new IssueJsonResponse()
		);

		$this->voteIssueAction = new VoteIssueAction(
			$this->payloadRequestParserService,
			$this->voteIssueService,
			new ErrorJsonResponse(),
			new IssueJsonResponse()
		);
	}
}