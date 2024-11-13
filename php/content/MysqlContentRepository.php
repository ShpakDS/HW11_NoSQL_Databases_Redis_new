<?php

class MysqlContentRepository implements ContentRepository
{
    public function find(int $contentId): ?Content
    {
        sleep(10);
        $content = ['id' => $contentId, 'title' => 'Title', 'body' => 'Body'];

        return new Content($content['id'], $content['title'], $content['body']);
    }

    public function save(Content $content): bool
    {
        return true;
    }
}