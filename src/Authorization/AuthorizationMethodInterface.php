<?php

namespace Jguillaumesio\PhpMercureHub\Authorization;

interface AuthorizationMethodInterface {
    public function authorize($request);
    public function setNextHandler(AuthorizationMethodInterface $nextHandler);
}