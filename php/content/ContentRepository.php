<?php

interface ContentRepository
{
    public function find(int $contentId): ?Content;

    public function save(Content $content): bool;
}