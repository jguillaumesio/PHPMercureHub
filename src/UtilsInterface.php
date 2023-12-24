<?php

namespace Jguillaumesio\PhpMercureHub;

interface UtilsInterface{
    public function setHeader($key, $value, $replace = true);
    public function getHeaders();
}