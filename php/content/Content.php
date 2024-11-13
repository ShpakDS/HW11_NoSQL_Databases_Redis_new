<?php

class Content {
    public function __construct(
        public int $id,
        public string $title,
        public string $body
    ) {
    }
}