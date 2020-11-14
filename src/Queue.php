<?php

declare(strict_types=1);

namespace Ilzrv\PhpBullQueue;

use Ilzrv\PhpBullQueue\Clients\PhpRedisQueue;
use Ilzrv\PhpBullQueue\Clients\PredisQueue;
use Ilzrv\PhpBullQueue\Clients\RedisQueue;
use Ilzrv\PhpBullQueue\DTOs\JobOpts;
use Ilzrv\PhpBullQueue\DTOs\QueueOpts;
use Ramsey\Uuid\Uuid;
use RuntimeException;

class Queue
{
    const DEFAULT_JOB_NAME = '__default__';

    private string $name;

    private QueueOpts $opts;

    public function __construct(string $name, QueueOpts $opts = null)
    {
        $this->name = $name;
        $this->opts = $opts ?: new QueueOpts();
    }

    /**
     * Adds a job to the queue.
     *
     * @param string $name
     * @param array $data
     * @param JobOpts|null $opts
     * @return mixed
     */
    public function add(string $name, array $data, JobOpts $opts = null)
    {
        $opts = $opts ?: new JobOpts([
            'timestamp' => (int) str_replace('.', '', microtime(true)),
        ]);

        $prefix = sprintf('%s:%s:', $this->opts->prefix, $this->name);

        return $this->client()->add(
            LuaScripts::add(),
            [
                $prefix.'wait',
                $prefix.'paused',
                $prefix.'meta-paused',
                $prefix.'id',
                $prefix.'delayed',
                $prefix.'priority',
                $prefix,
                $opts->customJobId,
                $name,
                json_encode($data),
                $opts->toJson(),
                $opts->timestamp,
                $opts->delay,
                $opts->delay ? $opts->timestamp + $opts->delay : 0,
                $opts->priority,
                $opts->lifo ? 'RPUSH' : 'LPUSH',
                (string) Uuid::uuid4()
            ],
            6
        );
    }

    /**
     * @return RedisQueue
     */
    protected function client()
    {
        switch ($this->opts->redis->client) {
            case 'predis':
                return new PredisQueue($this->opts->redis);
            case 'phpredis':
                return new PhpRedisQueue($this->opts->redis);
            default:
                throw new RuntimeException("{$this->opts->redis->client} client is not supported.");
        }
    }
}
