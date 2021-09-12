<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

final class IssueNotVotingException extends Exception
{
	#[Pure]
	public function __construct(int $issueId)
	{
		parent::__construct(sprintf(
			'Issue with ID \'%s\' is not voting.',
			$issueId
		));
	}
}