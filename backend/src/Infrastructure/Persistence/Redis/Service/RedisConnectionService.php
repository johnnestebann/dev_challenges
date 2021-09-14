<?php

namespace Workana\Infrastructure\Persistence\Redis\Service;

use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;

final class RedisConnectionService
{
	public function __invoke(): Redis
	{
		return RedisAdapter::createConnection(
			'redis://redis:6379/1'
		);
	}
}