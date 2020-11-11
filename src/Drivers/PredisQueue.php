<?php

declare(strict_types=1);

namespace Ilzrv\PhpBullQueue\Drivers;

use Ilzrv\PhpBullQueue\DTOs\RedisConfig;
use Predis\Client as Redis;

class PredisQueue implements RedisQueue
{
    protected Redis $client;

    public function __construct(RedisConfig $config, Redis $redis = null)
    {
        if (!is_null($redis)) {
            $this->client = $redis;
        } else {
            $this->client = new Redis([
                'host' => $config->host,
                'port' => $config->port,
            ]);
        }
    }

    public function add(string $script, array $args, int $numKeys)
    {
        return $this->client->eval($script, $numKeys, ...$args);
    }
}
