<?php

namespace Jguillaumesio\PhpMercureHub;

class Handler
{

    private $manager;

    public function __construct(){
        $this->manager = new MecureSubscriptionManager();
    }

    public function process($name){
        $topic = $this->manager->getTopic($name);
        if($topic === null){
            throw new \Error('TOPIC_DOESNT_EXIST');
        }

    }
}