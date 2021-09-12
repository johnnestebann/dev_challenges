<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

final class MemberAlreadyVotedOrPassedIssueException extends Exception
{
	#[Pure]
	public function __construct(string $username, int $issueId)
	{
		parent::__construct(
			sprintf(
				'Member \'%s\' already voted or passed Issue with ID \'%s\'.',
				$username,
				$issueId
			)
		);
	}
}