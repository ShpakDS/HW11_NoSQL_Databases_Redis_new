<?php

require 'RedisConnection.php';
require 'Cache.php';
require 'content/Content.php';
require 'content/ContentRepository.php';
require 'content/MysqlContentRepository.php';
require 'content/CacheContentRepositoryDecorator.php';

$sentinels = [
    ['host' => 'redis-sentinel1', 'port' => 26379],
    ['host' => 'redis-sentinel2', 'port' => 26379],
    ['host' => 'redis-sentinel3', 'port' => 26379]
];

$masterName = 'mymaster';

try {
    $redis = (new RedisConnection())->connect($sentinels, $masterName);
    $cache = new Cache($redis);

    $contentRepository = new MysqlContentRepository();
    $contentRepository = new CacheContentRepositoryDecorator($contentRepository, $cache, 10);

    $retrievedContent = $contentRepository->find(1);

    var_dump($retrievedContent);
} catch (Exception $e) {
    echo "Error: {$e->getMessage()}\n";
}