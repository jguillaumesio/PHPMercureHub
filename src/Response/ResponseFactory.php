<?php

namespace Jguillaumesio\PhpMercureHub\Response;

class ResponseFactory {

    public function __invoke($type) {
        return $this->createResponse($type);
    }

    public function createResponse($type) {
        switch ($type) {
            case 'html':
                return new HTMLMercureResponse();
            default:
                return new JSONMercureResponse();
        }
    }

}