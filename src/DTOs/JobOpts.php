<?php

declare(strict_types=1);

namespace Ilzrv\PhpBullQueue\DTOs;

class JobOpts extends DataTransferObject
{
    public string $customJobId = '';
    public int $priority = 0;
    public bool $lifo = false;
    public int $attempts = 1;
    public int $timestamp;
    public int $delay = 0;
}
