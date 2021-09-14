<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue\Exception;

use Exception;

final class InvalidVoteValueException extends Exception
{
	public function __construct()
	{
		parent::__construct('Invalid vote value.');
	}
}