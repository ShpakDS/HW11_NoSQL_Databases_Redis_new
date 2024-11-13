<?php

class Cache
{
    public function __construct(private readonly Redis $redis)
    {
    }

    public function getOrSet(string $key, callable $fallback, int $ttl): mixed
    {
        $cachedData = $this->redis->get($key);

        if ($cachedData !== false) {
            echo "Retrieved from cache\n";

            return unserialize($cachedData);
        }

        if (!$this->redis->set("lock:$key", "1", ["nx", "ex" => 5])) {
            echo "Another process updates the cache\n";

            sleep(1);

            return $this->getOrSet($key, $fallback, $ttl);
        }

        $data = $fallback();
        $this->set($key, $data, $ttl);

        $this->redis->del("lock:$key");

        echo "Retrieved from fallback\n";

        return $data;
    }

    public function set(string $key, mixed $data, int $ttl): void
    {
        $this->redis->setex($key, $ttl, serialize($data));
    }

}