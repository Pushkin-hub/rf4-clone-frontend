<?php

namespace Models;

class User {
    public $id;
    public $name;
    public $email;

    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
    }
}
