<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Delivery\Action\V1\Issue;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Workana\Application\Service\V1\Issue\CreateIssueService;
use Workana\Application\Service\V1\Issue\GetIssueByIdService;
use Workana\Application\Service\V1\Issue\JoinIssueService;
use Workana\Domain\Model\Issue\Exception\InvalidMemberException;
use Workana\Infrastructure\Delivery\Response\Json\V1\Error\ErrorJsonResponse;
use Workana\Infrastructure\Delivery\Response\Json\V1\Issue\IssueJsonResponse;
use Workana\Infrastructure\Delivery\Request\PayloadRequestParserService;

final class JoinOrCreateIssueAction
{
	private GetIssueByIdService $getIssueByIdService;

	private CreateIssueService $createIssueService;

	private PayloadRequestParserService $payloadRequestParserService;

	private JoinIssueService $joinIssueService;

	private ErrorJsonResponse $errorJsonResponse;

	private IssueJsonResponse $issueJsonResponse;

	public function __construct(
		GetIssueByIdService $getIssueByIdService,
		CreateIssueService $createIssueService,
		PayloadRequestParserService $payloadRequestParserService,
		JoinIssueService $joinIssueService,
		ErrorJsonResponse $errorJsonResponse,
		IssueJsonResponse $issueJsonResponse
	)
	{
		$this->getIssueByIdService = $getIssueByIdService;
		$this->createIssueService = $createIssueService;
		$this->payloadRequestParserService = $payloadRequestParserService;
		$this->joinIssueService = $joinIssueService;
		$this->errorJsonResponse = $errorJsonResponse;
		$this->issueJsonResponse = $issueJsonResponse;
	}

	public function __invoke(int $issueId, Request $request): JsonResponse
	{
		try {
			$data = ($this->payloadRequestParserService)($request->getContent());

			if (empty($data) || empty($data['name'])) {
				throw new InvalidMemberException();
			}

			try {
				$issue = ($this->getIssueByIdService)($issueId);
			} catch (Exception) {
				$issue = ($this->createIssueService)($issueId);
			}

			($this->joinIssueService)($issueId, $issue, $data['name']);
		} catch (Exception $e) {
			return ($this->errorJsonResponse)($e->getMessage());
		}

		return ($this->issueJsonResponse)($issue);
	}
}