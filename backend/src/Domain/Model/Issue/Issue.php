<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

final class Issue
{
	private string $status;

	private array $members;

	private int $avg;

	private function __construct(string $status = 'voting', array $members = [], int $avg = 0)
	{
		$this->status = $status;
		$this->members = $members;
		$this->avg = $avg;
	}

	/**
	 * @param string $status
	 * @param array $members
	 * @param int $avg
	 * @return static
	 */
	#[Pure]
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

	public function getMembers(): array
	{
		return $this->members;
	}

	public function joinMember(string $username): bool
	{
		$joined = false;
		$qtyMembers = count($this->members);
		$i = 0;

		while (false === $joined && $i < $qtyMembers) {
			if ($username === $this->members[$i]['name']) {
				$joined = true;
			}

			$i++;
		}

		if (false === $joined) {
			$this->members[] = [
				"name" => $username,
				"status" => 'waiting',
				"value" => 0
			];
		}

		return !$joined;
	}

	public function getAvg(): int
	{
		return $this->avg;
	}

	public function setAvg(int $avg): void
	{
		$this->avg = $avg;
	}

	#[ArrayShape(["status" => "string", "members" => "array", "avg" => "int"])]
	public function toArray(): array
	{
		return [
			"status" => $this->status,
			"members" => $this->members,
			"avg" => $this->avg
		];
	}
}