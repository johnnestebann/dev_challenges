<?php

namespace Workana\Domain\Model\Issue\Exception;

use Exception;

final class IssueNotFoundException extends Exception
{
	private const STATUS_CODE = 404;

	public function __construct(int $issueId)
	{
		parent::__construct(sprintf(
			'Issue with ID: %s not found.',
			$issueId
		),self::STATUS_CODE);
	}
}