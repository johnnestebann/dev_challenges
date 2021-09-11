<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue;

use JetBrains\PhpStorm\Pure;

final class Issue
{
	public const STATUSES = [
		0 => 'VOTING',
		1 => 'REVEAL'
	];

	private string $status;

	private array $members;

	private int $avg;

	private function __construct(string $status = 'VOTING', array $members = [], int $avg = 0)
	{
		$this->status = $status;
		$this->members = $members;
		$this->avg = $avg;
	}

	#[Pure] public static function create(string $status, array $members, int $avg): self
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

	public function getMembers(): array
	{
		return $this->members;
	}

	public function addMember(array $member): void
	{
		if (false === in_array($member, $this->getMembers(), true)) {
			$this->members[] = $member;
		}
	}

	public function getAvg(): int
	{
		return $this->avg;
	}

	public function setAvg(int $avg): void
	{
		$this->avg = $avg;
	}
}