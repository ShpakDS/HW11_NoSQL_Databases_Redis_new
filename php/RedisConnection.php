<?php

declare(strict_types=1);

class RedisConnection
{
    /**
     * Connects to the Redis primary instance via Sentinel.
     *
     * @param array $sentinels Array of sentinel information with 'host' and 'port' keys
     * @param string $masterName Name of the Redis master
     * @return Redis
     * @throws Exception If unable to connect to master
     */
    public function connect(array $sentinels, string $masterName): Redis
    {
        [$masterHost, $masterPort] = $this->getMasterFromSentinel($sentinels, $masterName);

        $redis = new Redis();
        if (!$redis->connect($masterHost, (int)$masterPort)) {
            throw new Exception(sprintf('Failed to connect to Redis master at %s:%d', $masterHost, $masterPort));
        }

        return $redis;
    }

    /**
     * Retrieves the master host and port from Sentinel.
     *
     * @param array $sentinels Array of sentinel information with 'host' and 'port' keys
     * @param string $masterName Name of the Redis master
     * @return array Array with master host and port
     * @throws Exception If unable to get the master address from any Sentinel
     */
    private function getMasterFromSentinel(array $sentinels, string $masterName): array
    {
        foreach ($sentinels as $sentinelInfo) {
            try {
                $sentinel = new Redis();
                $sentinel->connect($sentinelInfo['host'], (int)$sentinelInfo['port']);

                $masterAddr = $sentinel->rawCommand(
                    'SENTINEL', 'get-master-addr-by-name',
                    $masterName
                );
                if (is_array($masterAddr) && count($masterAddr) === 2) {
                    return $masterAddr;
                }
            } catch (Exception $e) {
                error_log(sprintf(
                    'Failed to connect to Sentinel at %s:%d, error: %s',
                    $sentinelInfo['host'],
                    $sentinelInfo['port'],
                    $e->getMessage()
                ));
            }
        }

        throw new Exception('Failed to retrieve master address from all provided Sentinels.');
    }
}