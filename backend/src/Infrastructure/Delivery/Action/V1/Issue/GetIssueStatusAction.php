<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Delivery\Action\V1\Issue;

use Workana\Application\Service\V1\Issue\GetIssueByIdService;
use Workana\Domain\Model\Issue\Exception\IssueNotFoundException;
use Workana\Infrastructure\Delivery\Response\Json\V1\Issue\IssueJsonResponse;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetIssueStatusAction
{
	private GetIssueByIdService $getIssueByIdService;

	private IssueJsonResponse $jsonResponse;

	public function __construct(
		GetIssueByIdService $getIssueByIdService,
		IssueJsonResponse   $jsonResponse
	)
	{
		$this->getIssueByIdService = $getIssueByIdService;
		$this->jsonResponse = $jsonResponse;
	}

	/**
	 * @throws IssueNotFoundException
	 * @throws InvalidArgumentException
	 */
	public function __invoke(int $issueId): JsonResponse
	{
		$issue = ($this->getIssueByIdService)($issueId);

		if (null === $issue) {
			// TODO create middleware to response errors
			throw new IssueNotFoundException($issueId);
		}

		return ($this->jsonResponse)($issue);
	}
}