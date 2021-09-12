<?php

declare(strict_types=1);

namespace Workana\Infrastructure\Delivery\Request;

use JsonException;

final class PayloadRequestParserService
{
	/**
	 * @throws JsonException
	 */
	public function __invoke(string $payload): array
	{
		return json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
	}
}