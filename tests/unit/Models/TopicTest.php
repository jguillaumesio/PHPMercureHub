<?php

namespace Jguillaumesio\PhpMercureHub\unit\Models;

use Jguillaumesio\PhpMercureHub\Models\Topic;
use PHPUnit\Framework\TestCase;

class TopicTest extends TestCase {

    public function testConstruct(){
        $topic = new Topic('https://example.com/a-topic');
    }

}