<?php

namespace Workana\Domain\Model\Issue\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

final class IssueNotFoundException extends Exception
{
	#[Pure]
	public function __construct(int $issueId)
	{
		parent::__construct(sprintf(
			'Issue with ID: %s not found.',
			$issueId
		));
	}
}