<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Delivery\Action\V1\Issue;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Workana\Application\Service\V1\Issue\CreateIssueService;
use Workana\Application\Service\V1\Issue\GetIssueByIdService;
use Workana\Application\Service\V1\Issue\JoinIssueService;
use Workana\Infrastructure\Delivery\Response\Json\V1\Issue\IssueJsonResponse;

final class JoinOrCreateIssueAction
{
	private GetIssueByIdService $getIssueByIdService;

	private JoinIssueService $joinIssueService;

	private CreateIssueService $createIssueService;

	private IssueJsonResponse $jsonResponse;

	public function __construct(
		GetIssueByIdService $getIssueByIdService,
		JoinIssueService $joinIssueService,
		CreateIssueService $createIssueService,
		IssueJsonResponse $jsonResponse
	)
	{
		$this->getIssueByIdService = $getIssueByIdService;
		$this->joinIssueService = $joinIssueService;
		$this->createIssueService = $createIssueService;
		$this->jsonResponse = $jsonResponse;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function __invoke(int $issueId): JsonResponse
	{
		// TODO first get user
		$issue = ($this->getIssueByIdService)($issueId);

		if (null === $issue) {
			$issue = ($this->createIssueService)($issueId);
		}

		($this->joinIssueService)($issueId);

		return ($this->jsonResponse)($issue);
	}
}