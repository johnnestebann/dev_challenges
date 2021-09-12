<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

final class InvalidMemberException extends Exception
{
	#[Pure]
	public function __construct()
	{
		parent::__construct('Invalid member');
	}
}