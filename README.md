# PHP Bull Queue
[![Latest Stable Version](https://img.shields.io/packagist/v/ilzrv/php-bull-queue.svg)](https://packagist.org/packages/ilzrv/php-bull-queue)
[![Total Downloads](https://img.shields.io/packagist/dt/ilzrv/php-bull-queue.svg)](https://packagist.org/packages/ilzrv/php-bull-queue)
[![License](https://img.shields.io/github/license/ilzrv/php-bull-queue.svg)](https://packagist.org/packages/ilzrv/php-bull-queue)

At the moment this library provides the ability to add jobs to the [Bull Queue](https://github.com/OptimalBits/bull).

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
