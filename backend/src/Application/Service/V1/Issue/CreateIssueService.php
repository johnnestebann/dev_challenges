<?php

declare(strict_types=1);

namespace Workana\Application\Service\V1\Issue;

use Workana\Domain\Model\Issue\Issue;

final class CreateIssueService
{
	public function __invoke(): Issue
	{
		return Issue::create('', [], 0);
	}
}