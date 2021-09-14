<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Delivery\Action\V1\Issue;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Workana\Application\Service\V1\Issue\VoteIssueService;
use Workana\Domain\Model\Issue\Exception\InvalidMemberException;
use Workana\Domain\Model\Issue\Exception\InvalidVoteValueException;
use Workana\Infrastructure\Delivery\Request\PayloadRequestParserService;
use Workana\Infrastructure\Delivery\Response\Json\V1\Error\ErrorJsonResponse;
use Workana\Infrastructure\Delivery\Response\Json\V1\Issue\IssueJsonResponse;

final class VoteIssueAction
{
	private PayloadRequestParserService $payloadRequestParserService;

	private VoteIssueService $voteIssueService;

	private ErrorJsonResponse $errorJsonResponse;

	private IssueJsonResponse $issueJsonResponse;

	public function __construct(
		PayloadRequestParserService $payloadRequestParserService,
		VoteIssueService $voteIssueService,
		ErrorJsonResponse $errorJsonResponse,
		IssueJsonResponse $issueJsonResponse
	)
	{
		$this->payloadRequestParserService = $payloadRequestParserService;
		$this->voteIssueService = $voteIssueService;
		$this->errorJsonResponse = $errorJsonResponse;
		$this->issueJsonResponse = $issueJsonResponse;
	}

	public function __invoke(int $issueId, Request $request): JsonResponse
	{
		try {
			$data = ($this->payloadRequestParserService)((string) $request->getContent());

			if (empty($data) || empty($data['vote'])) {
				throw new InvalidVoteValueException();
			}

			if (empty($data['name'])) {
				throw new InvalidMemberException();
			}

			$issue = ($this->voteIssueService)($issueId, $data['name'], $data['vote']);
		} catch (Exception $e) {
			return ($this->errorJsonResponse)($e->getMessage());
		}

		return ($this->issueJsonResponse)($issue);
	}
}