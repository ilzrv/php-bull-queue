# PHP Bull Queue
[![Latest Stable Version](https://img.shields.io/packagist/v/ilzrv/php-bull-queue.svg)](https://packagist.org/packages/ilzrv/php-bull-queue)
[![Total Downloads](https://img.shields.io/packagist/dt/ilzrv/php-bull-queue.svg)](https://packagist.org/packages/ilzrv/php-bull-queue)
[![License](https://img.shields.io/github/license/ilzrv/php-bull-queue.svg)](https://packagist.org/packages/ilzrv/php-bull-queue)

At the moment this library provides the ability to add jobs to the [Bull Queue](https://github.com/OptimalBits/bull).

## Requirements
 * PHP 7.4+
 * [PhpRedis](https://github.com/phpredis/phpredis) (default) or [Predis](https://github.com/predis/predis)
 * [ramsey/uuid](https://github.com/ramsey/uuid)

## Installation

You can install the package via composer:

```bash
composer require ilzrv/php-bull-queue
```

## Usage

To add a job to the queue, you can use the following example:

```php
<?php

use Ilzrv\PhpBullQueue\Queue;

$videoQueue = new Queue('videoQueue');

$videoQueue->add(Queue::DEFAULT_JOB_NAME, [
    'video' => 'http://example.com/video1.mov'
]);
```

If you want to use `predis` as Redis client:

```php
<?php

use Ilzrv\PhpBullQueue\Queue;
use Ilzrv\PhpBullQueue\DTOs\QueueOpts;
use Ilzrv\PhpBullQueue\DTOs\RedisConfig;

$videoQueue = new Queue(
    'videoQueue',
    new QueueOpts([
        'redis' => new RedisConfig([
            'driver' => 'predis',
        ]),
    ])
);

$videoQueue->add(Queue::DEFAULT_JOB_NAME, [
    'video' => 'http://example.com/video1.mov'
]);

```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
