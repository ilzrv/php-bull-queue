<?php

declare(strict_types=1);

namespace Ilzrv\PhpBullQueue\DTOs;

class QueueOpts extends DataTransferObject
{
    public RedisConfig $redis;
    public string $prefix = 'bull';
}
