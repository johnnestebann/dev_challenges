<?php

namespace Workana\Tests\Infrastructure\Delivery\Action\V1\Issue;

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
use Workana\Infrastructure\Persistence\Redis\Repository\RedisIssueRepository;
use Workana\Infrastructure\Persistence\Redis\Service\RedisConnectionService;

abstract class IssueServiceActionTest extends TestCase
{
    protected IssueRepositoryInterface $issueRepository;

    protected GetIssueByIdService $getIssueByIdService;

    protected CreateIssueService $createIssueService;

    protected JoinIssueService $joinIssueService;

    protected VoteIssueService $voteIssueService;

    protected PayloadRequestParserService $payloadRequestParserService;

    protected GetIssueStatusAction $getIssueStatusAction;

    protected JoinOrCreateIssueAction $joinOrCreateIssueAction;

    protected VoteIssueAction $voteIssueAction;

    protected ErrorJsonResponse $errorJsonResponse;

    protected IssueJsonResponse $issueJsonResponse;

    protected function setUp(): void
    {
        $this->issueRepository = new RedisIssueRepository(
            new RedisConnectionService()
        );

        $this->payloadRequestParserService = new PayloadRequestParserService();

        $this->getIssueByIdService = new GetIssueByIdService($this->issueRepository);
        $this->createIssueService = new CreateIssueService($this->issueRepository);
        $this->joinIssueService = new JoinIssueService($this->issueRepository);
        $this->voteIssueService = new VoteIssueService(
            $this->getIssueByIdService,
            $this->issueRepository
        );

        $this->errorJsonResponse = new ErrorJsonResponse();
        $this->issueJsonResponse = new IssueJsonResponse();


        $this->getIssueStatusAction = new GetIssueStatusAction(
            $this->getIssueByIdService,
            $this->issueJsonResponse
        );

        $this->joinOrCreateIssueAction = new JoinOrCreateIssueAction(
            $this->getIssueByIdService,
            $this->createIssueService,
            $this->payloadRequestParserService,
            $this->joinIssueService,
            $this->issueJsonResponse
        );

        $this->voteIssueAction = new VoteIssueAction(
            $this->payloadRequestParserService,
            $this->voteIssueService,
            $this->issueJsonResponse
        );
    }
}
