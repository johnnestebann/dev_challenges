<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue\Exception;

use Exception;

final class MemberNotJoinedToIssueException extends Exception
{
    public function __construct(string $username, int $issueId)
    {
        parent::__construct(
            sprintf(
                'Member \'%s\' not joined to Issue with ID \'%s\'.',
                $username,
                $issueId
            )
        );
    }
}
