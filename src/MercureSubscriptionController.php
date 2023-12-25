<?php

namespace Jguillaumesio\PhpMercureHub;

class MercureSubscriptionController {

    private $manager;

    public function __construct(){
        $this->manager = new MecureSubscriptionManager();
    }

    public function publication(){
        $headers = $this->manager->getRequest()['headers'];
        if(!\is_array($headers) || \array_key_exists('content-type', $headers) || $headers['content-type'] !== 'application/x-www-form-urlencoded'){
            throw new \Error('INVALID_CONTENT_TYPE');
        }
        extract($_GET);
        $topic = $this->manager->getTopic($topic ?? '');
        if($topic === null){
            throw new \Error('INVALID_OR_MISSING_TOPIC');
        }
    }

    public function subscription(){
        extract($_GET);
        if(isset($id) && $id[0] === '#'){
            throw new \Error('INVALID_ID');
        }
        //check content type
        //TODO retrieve topics
        $this->manager->setSubscriptionHeaders();
    }
}