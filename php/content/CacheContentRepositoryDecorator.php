<?php

class CacheContentRepositoryDecorator implements ContentRepository
{
    public function __construct(
        private readonly ContentRepository $contentRepository,
        private readonly Cache $cache,
        private readonly int $ttl = 3600
    ) {
    }

    public function find(int $contentId): ?Content
    {
        $key = "content:$contentId";

        return $this->cache->getOrSet($key, fn() => $this->contentRepository->find($contentId), $this->ttl);
    }

    public function save(Content $content): bool {
        $result = $this->contentRepository->save($content);

        if ($result) {
            $key = "content:{$content->id}";
            $this->cache->set($key, $content, $this->ttl);
        }

        return $result;
    }
}