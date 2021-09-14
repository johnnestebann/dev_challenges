<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue;

class Issue
{
    public const REVEAL = 'reveal';

    public const VOTING = 'voting';

    private string $status;

    /** @var array[] */
    private array $members;

    private int $avg;

    /**
     * @param array[] $members
     */
    private function __construct(string $status = 'voting', array $members = [], int $avg = 0)
    {
        $this->status = $status;
        $this->members = $members;
        $this->avg = $avg;
    }

    /**
     * @param array[] $members
     */
    public static function create(string $status = 'voting', array $members = [], int $avg = 0): self
    {
        return new self($status, $members, $avg);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function addMember(string $username): bool
    {
        $isMember = $this->hasMember($username);

        if (false === $isMember) {
            $this->members[$username] = [
                "status" => 'waiting',
                "value" => 0
            ];
        }

        return !$isMember;
    }

    public function hasMember(string $username): bool
    {
        return array_key_exists($username, $this->members);
    }

    public function memberAlreadyVotedOrPassed(string $username): bool
    {
        $alreadyVotedOrPassed = false;

        if ('waiting' !== $this->members[$username]['status']) {
            $alreadyVotedOrPassed = true;
        }

        return $alreadyVotedOrPassed;
    }

    public function memberVote(string $username, int $vote): void
    {
        if (-1 === $vote) {
            $this->members[$username] = [
                'status' => 'passed',
                'value' => -1
            ];
        } else {
            $this->members[$username] = [
                'status' => 'voted',
                'value' => $vote
            ];
        }

        $this->setAvg();

        if (true === $this->allMemberHasVoted()) {
            $this->status = self::REVEAL;
        }
    }

    private function setAvg(): void
    {
        $validMembers = 0;
        $avg = 0;

        foreach ($this->members as $member) {
            if ('voted' === $member['status']) {
                $avg += $member['value'];
                $validMembers++;
            }
        }

        $this->avg = 0 !== $validMembers ? (int)($avg / $validMembers) : 0;
    }

    private function allMemberHasVoted(): bool
    {
        $voteFinished = true;

        foreach ($this->members as $member) {
            if ('waiting' === $member['status']) {
                $voteFinished = false;
                break;
            }
        }

        return $voteFinished;
    }

    /**
     * @return array<string, array<array>|int|string>
     */
    public function toFullArray(): array
    {
        return [
            "status" => $this->status,
            "members" => $this->members,
            "avg" => $this->avg
        ];
    }

    /**
     * @return array<string, array<array>|int|string>
     */
    public function toArray(): array
    {
        return [
            "status" => $this->status,
            "members" => $this->getMembers(),
            "avg" => $this->getAvg()
        ];
    }

    /**
     * @return array[]
     */
    public function getMembers(): array
    {
        if (self::VOTING === $this->status) {
            foreach ($this->members as &$member) {
                $member['value'] = 0;
            }
        }

        return $this->members;
    }

    public function getAvg(): int
    {
        return self::VOTING === $this->status ? 0 : $this->avg;
    }
}
