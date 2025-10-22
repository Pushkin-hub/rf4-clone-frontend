<?php

namespace Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Models\User;

class AuthController {

    public function login(Request $request, Response $response) {
        $data = $request->getParsedBody();
        if ($data['email'] === 'test@example.com' && $data['password'] === 'password') {
            $user = new User(['id' => 1, 'name' => 'Test User', 'email' => 'test@example.com']);
            return $response->withJson(['message' => 'Login successful', 'token' => 'your_jwt_token']);
        } else {
            return $response->withStatus(401)->withJson(['message' => 'Invalid credentials']);
        }
    }

    public function register(Request $request, Response $response) {
        $data = $request->getParsedBody();
        return $response->withJson(['message' => 'Registration successful']);
    }
}
