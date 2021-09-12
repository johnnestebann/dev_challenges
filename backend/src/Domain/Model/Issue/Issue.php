<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

final class Issue
{
	public const VOTING = 'voting';

	public const REVEAL = 'reveal';

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

	public function hasMember(string $username): bool
	{
		$isMember = false;
		$qtyMembers = count($this->members);
		$i = 0;

		while (false === $isMember && $i < $qtyMembers) {
			if ($username === $this->members[$i]['name']) {
				$isMember = true;
			}

			$i++;
		}

		return $isMember;
	}

	public function addMember(string $username): bool
	{
		$isMember = $this->hasMember($username);

		if (false === $isMember) {
			$this->members[] = [
				"name" => $username,
				"status" => 'waiting',
				"value" => 0
			];
		}

		return !$isMember;
	}

	public function memberAlreadyVotedOrPassed(string $username): bool
	{
		$alreadyVotedOrPassed = false;
		$qtyMembers = count($this->members);
		$i = 0;

		while (false === $alreadyVotedOrPassed && $i < $qtyMembers) {
			if (
				($username === $this->members[$i]['name']) &&
				('waiting' !== $this->members[$i]['status'])
			) {
				$alreadyVotedOrPassed = true;
			}

			$i++;
		}

		return $alreadyVotedOrPassed;
	}

	/**
	 * TODO change members array structure adding username as key
	 *
	 *
	 * @param string $username
	 * @param int $vote
	 */
	public function memberVote(string $username, int $vote): void
	{
		$data = [
			'name' => $username,
			'status' => 'voted',
			'value' => $vote
		];


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