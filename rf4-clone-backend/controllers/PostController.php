<?php

namespace Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Models\Post;

class PostController {

    public function index(Request $request, Response $response) {
        $posts = [
            ['id' => 1, 'title' => 'First post', 'content' => 'Content of first post'],
            ['id' => 2, 'title' => 'Second post', 'content' => 'Content of second post']
        ];
        return $response->withJson($posts);
    }

    public function store(Request $request, Response $response) {
        $data = $request->getParsedBody();
        return $response->withStatus(201)->withJson(['message' => 'Post created']);
    }

    public function show(Request $request, Response $response, $id) {
        $post = ['id' => $id, 'title' => 'Post ' . $id, 'content' => 'Content of post ' . $id];
        return $response->withJson($post);
    }

    public function update(Request $request, Response $response, $id) {
        $data = $request->getParsedBody();
        return $response->withJson(['message' => 'Post updated']);
    }

    public function destroy(Request $request, Response $response, $id) {
        return $response->withJson(['message' => 'Post deleted']);
    }
}
