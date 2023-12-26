<?php

namespace Jguillaumesio\PhpMercureHub\Models;

class Topic
{
    private $name;
    private $subscribers;

    public function __construct($name){
        $this->name = $name;
        $this->subscribers = [];
    }

    public function subscribe($subscriber = null){
        if (!in_array($subscriber, $this->subscribers, true)) {
            $this->subscribers[] = $subscriber;
        }
    }

    public function getSubscriptions(){
        return \array_map(fn($subscriber) => [
            'topic' => $this->name,
            'subscriber' => $subscriber->id
        ], $this->subscribers);
    }
}