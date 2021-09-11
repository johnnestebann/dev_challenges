<?php

namespace Workana\Infrastructure\Persistence\Redis\Service;

use Predis\Client;
use Predis\ClientInterface;
use Redis;
use RedisCluster;
use Symfony\Component\Cache\Adapter\RedisAdapter;

final class RedisConnectionService
{
	public function __invoke(): ClientInterface|RedisCluster|Client|Redis
	{
		return RedisAdapter::createConnection(
			'redis://redis:6379/1'
		);
	}
}