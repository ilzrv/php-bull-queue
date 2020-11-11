<?php

declare(strict_types=1);

namespace Ilzrv\PhpBullQueue\Drivers;

interface RedisQueue
{
    public function add(string $script, array $args, int $numKeys);
}
