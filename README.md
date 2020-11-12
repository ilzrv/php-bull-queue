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

If you want to use `predis` as Redis client (example configuration):

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
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => '',
        ]),
    ])
);

$videoQueue->add(Queue::DEFAULT_JOB_NAME, [
    'video' => 'http://example.com/video1.mov'
]);

```

## Configurations

All is configured via classes:

* `Ilzrv\PhpBullQueue\DTOs\RedisConfig`
* `Ilzrv\PhpBullQueue\DTOs\QueueOpts`
* `Ilzrv\PhpBullQueue\DTOs\JobOpts`

### RedisConfig
* `driver` (string) Redis driver. Can be `phpredis` or `predis`. Default: `phpredis`
* `host` (string) Redis host. Default: `127.0.0.1`
* `port` (int) Redis port. Default: `6379`
* `password` (string) Redis password. Default: `''`

### QueueOpts
* `redis` (RedisConfig Object) Redis Configuration.
* `prefix` (string) Queue prefix. Default: `bull`

### JobOpts
* `customJobId` (string) Custom JobId. Default: `0`
* `priority` (int) Job priority. Default: `0`
* `lifo` (bool) Last In, First Out. Default: `false`
* `attempts` (int) Job attempts. Default: `1`
* `timestamp` (int) Current timestamp.
* `delay` (int) Job delay. Default: `0`

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
