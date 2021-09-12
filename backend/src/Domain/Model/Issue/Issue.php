<?php

declare(strict_types=1);

namespace Workana\Domain\Model\Issue;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

final class Issue
{
	public const REVEAL = 'reveal';

	public const VOTING = 'voting';

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
		if (self::VOTING === $this->status) {
			foreach ($this->members as &$member) {
				$member['value'] = 0;
			}
		}

		return $this->members;
	}

	public function hasMember(string $username): bool
	{
		return array_key_exists($username, $this->members);
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

	public function memberAlreadyVotedOrPassed(string $username): bool
	{
		$alreadyVotedOrPassed = false;

		if ('waiting' !== $this->members[$username]['status']) {
			$alreadyVotedOrPassed = true;
		}

		return $alreadyVotedOrPassed;
	}

	// TODO members vote doesnt save
	public function memberVote(string $username, int $vote): void
	{
		$this->members[$username] = [
			'status' => 'voted',
			'value' => $vote
		];

		$this->avg += (int) ($vote / count($this->members));

		if (true === $this->allMemberHasVoted()) {
			$this->status = self::REVEAL;
		}
	}

	private function allMemberHasVoted(): bool
	{
		$voteFinished = true;

		foreach ($this->members as $member) {
			if ('voted' !== $member['status']) {
				$voteFinished = false;
			}
		}

		return $voteFinished;
	}

	private function getAvg(): int
	{
		return self::VOTING === $this->status ? 0 : $this->avg;
	}

	#[ArrayShape(["status" => "string", "members" => "array", "avg" => "int"])]
	public function toArray(): array
	{
		return [
			"status" => $this->status,
			"members" => $this->getMembers(),
			"avg" => $this->getAvg()
		];
	}
}