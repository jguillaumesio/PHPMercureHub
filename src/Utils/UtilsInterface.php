<?php

namespace Jguillaumesio\PhpMercureHub\Utils;

interface UtilsInterface{
    public function setHeader($key, $value, $replace = true);
    public function getHeaders();
    public function getQueryParams();
    public function getCookies();
}