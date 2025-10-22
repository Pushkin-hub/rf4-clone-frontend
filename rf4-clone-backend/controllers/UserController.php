<?php

namespace Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class UserController {

    public function index(Request $request, Response $response) {
        return $response->withJson(['message' => 'Users list']);
    }
}
