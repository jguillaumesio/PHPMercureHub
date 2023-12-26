<?php

namespace Jguillaumesio\PhpMercureHub\Models;

use Ramsey\Uuid\Uuid;

class Subscriber {

    private $id;
    private $subscribedTopics = [];

    public function __construct($topic){
        $this->id = Uuid::uuid4();
        $this->subscribe($topic);
    }

    public function subscribe($topic){
        if (!in_array($topic, $this->subscribedTopics, true)) {
            $topic->subscribe($this);
            $this->subscribedTopics[] = $topic;
        }
    }

}