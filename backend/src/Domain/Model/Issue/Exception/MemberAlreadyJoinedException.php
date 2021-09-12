<?php

namespace Workana\Domain\Model\Issue\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

class MemberAlreadyJoinedException extends Exception
{
	#[Pure]
	public function __construct(string $username, int $issueId)
	{
		parent::__construct(
			sprintf(
				'Member \'%s\' already joined to Issue with ID \'%s\'',
				$username,
				$issueId
			)
		);
	}
}