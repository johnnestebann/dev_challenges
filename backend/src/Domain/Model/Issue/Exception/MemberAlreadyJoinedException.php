<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue\Exception;

use Exception;

class MemberAlreadyJoinedException extends Exception
{
    public function __construct(string $username, int $issueId)
    {
        parent::__construct(
            sprintf(
                'Member \'%s\' already joined to Issue with ID \'%s\'.',
                $username,
                $issueId
            )
        );
    }
}
