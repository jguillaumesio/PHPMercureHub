<?php

namespace Jguillaumesio\PhpMercureHub\Response;
interface MercureResponse
{
    public function generate($resource, $values, $request);
}