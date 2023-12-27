<?php

namespace Jguillaumesio\PhpMercureHub\Authorization;

interface AuthorizationMethodInterface {
    public function getJWT($request);
    public function setNextHandler(AuthorizationMethodInterface $nextHandler);
    public function next($request);
}