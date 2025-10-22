<?php

namespace Models;

class Post {
    public $id;
    public $title;
    public $content;

    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->content = $data['content'] ?? null;
    }
}
