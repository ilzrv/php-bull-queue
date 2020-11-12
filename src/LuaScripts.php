<?php

declare(strict_types=1);

namespace Ilzrv\PhpBullQueue;

class LuaScripts
{
    /**
     * Adds a job to the queue by doing the following:
     *
     * KEYS[1] 'wait',
     * KEYS[2] 'paused'
     * KEYS[3] 'meta-paused'
     * KEYS[4] 'id'
     * KEYS[5] 'delayed'
     * KEYS[6] 'priority'
     *
     * ARGV[1] key prefix
     * ARGV[2] custom id (will not generate one automatically)
     * ARGV[3] name
     * ARGV[4] data (json stringified job data)
     * ARGV[5] opts (json stringified job opts)
     * ARGV[6] timestamp
     * ARGV[7] delay
     * ARGV[8] delayedTimestamp
     * ARGV[9] priority
     * ARGV[10] LIFO
     * ARGV[11] token
     *
     * @see https://github.com/OptimalBits/bull/blob/develop/lib/commands/addJob-6.lua
     * @return string
     */
    public static function add()
    {
        return <<<'LUA'
local jobId
local jobIdKey
local rcall = redis.call

local jobCounter = rcall("INCR", KEYS[4])

if ARGV[2] == "" then
  jobId = jobCounter
  jobIdKey = ARGV[1] .. jobId
else
  jobId = ARGV[2]
  jobIdKey = ARGV[1] .. jobId
  if rcall("EXISTS", jobIdKey) == 1 then
    return jobId .. ""
  end
end

rcall("HMSET", jobIdKey, "name", ARGV[3], "data", ARGV[4], "opts", ARGV[5], "timestamp", ARGV[6], "delay", ARGV[7], "priority", ARGV[9])

local delayedTimestamp = tonumber(ARGV[8])
if(delayedTimestamp ~= 0) then
  local timestamp = delayedTimestamp * 0x1000 + bit.band(jobCounter, 0xfff)
  rcall("ZADD", KEYS[5], timestamp, jobId)
  rcall("PUBLISH", KEYS[5], delayedTimestamp)
else
  local target
  local paused
  if rcall("EXISTS", KEYS[3]) ~= 1 then
    target = KEYS[1]
    paused = false
  else
    target = KEYS[2]
    paused = true
  end

  local priority = tonumber(ARGV[9])
  if priority == 0 then
    rcall(ARGV[10], target, jobId)
  else
    rcall("ZADD", KEYS[6], priority, jobId)
    local count = rcall("ZCOUNT", KEYS[6], 0, priority)

    local len = rcall("LLEN", target)
    local id = rcall("LINDEX", target, len - (count-1))
    if id then
      rcall("LINSERT", target, "BEFORE", id, jobId)
    else
      rcall("RPUSH", target, jobId)
    end

  end

  rcall("PUBLISH", KEYS[1] .. "ing@" .. ARGV[11], jobId)
end

return jobId .. ""
LUA;
    }
}
