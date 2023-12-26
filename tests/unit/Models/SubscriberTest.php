<?php

namespace Jguillaumesio\PhpMercureHub\unit\Models;

use Jguillaumesio\PhpMercureHub\Models\Subscriber;
use Jguillaumesio\PhpMercureHub\Models\Topic;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    public function testConstruct(){
        $topic = new Topic('https://example.com/a-topic');
        $subscriber = new Subscriber($topic);
    }
}