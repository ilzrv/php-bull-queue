<?php

declare(strict_types=1);

namespace Ilzrv\PhpBullQueue\Drivers;

use Ilzrv\PhpBullQueue\DTOs\RedisConfig;
use Redis;

class PhpRedisQueue implements RedisQueue
{
    protected Redis $client;

    public function __construct(RedisConfig $config, Redis $redis = null)
    {
        if (!is_null($redis)) {
            $this->client = $redis;
        } else {
            $this->client = new Redis();

            $this->client->connect($config->host, $config->port);
            $this->client->auth($config->password);
        }
    }

    public function add(string $script, array $args, int $numKeys)
    {

        return $this->client->eval($script, $args, $numKeys);
    }
}
