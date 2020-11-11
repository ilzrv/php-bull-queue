<?php

declare(strict_types=1);

namespace Ilzrv\PhpBullQueue\DTOs;

class RedisConfig extends DataTransferObject
{
    public string $driver = 'phpredis';
    public string $host = '127.0.0.1';
    public int $port = 6379;
}
