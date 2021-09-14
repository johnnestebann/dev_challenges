<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue\Exception;

use Exception;

final class IssueNotVotingException extends Exception
{
	public function __construct(int $issueId)
	{
		parent::__construct(sprintf(
			'Issue with ID \'%s\' is not voting.',
			$issueId
		));
	}
}