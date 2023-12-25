<?php

namespace Jguillaumesio\PhpMercureHub\Authorization;

abstract class AbstractAuthorization
{
    private $nextHandler;

    public function setNextHandler(AuthorizationMethodInterface $nextHandler) {
        $this->nextHandler = $nextHandler;
    }

    public function next(){
        return ($this->nextHandler !== null) ? $this->nextHandler->authorize() : null;
    }
}