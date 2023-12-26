<?php

namespace Jguillaumesio\PhpMercureHub;

use Jguillaumesio\PhpMercureHub\Authorization\AuthorizationManager;
use Jguillaumesio\PhpMercureHub\Utils\TopicUtils;

class SubscriptionController {

    private $subscriptionManager;
    private $authManager;
    private $topicUtils;

    public function __construct(){
        $this->subscriptionManager = new SubscriptionManager();
        $this->authManager = new AuthorizationManager();
        $this->topicUtils = new TopicUtils();
    }

    public function publication(){
        $request = $this->subscriptionManager->getRequest();
        $headers = $request['headers'];
        if(!\is_array($headers) || \array_key_exists('content-type', $headers) || $headers['content-type'] !== 'application/x-www-form-urlencoded'){
            throw new \Error('INVALID_CONTENT_TYPE');
        }
        $jwtPayload = $this->authManager->getJWTPayload($request);
        if($jwtPayload === null){
            throw new \Error('INVALID_OR_MISSING_AUTHORIZATION');
        }
        if(
            !\array_key_exists('mercure', $jwtPayload) ||
            !\array_key_exists('publish', $jwtPayload['mercure']) ||
            !\is_array($jwtPayload['mercure']['publish'])
        ){
            throw new \Error('INVALID_OR_MISSING_AUTHORIZATION');
        }
        $topics = \array_filter(
            \is_array($_GET['topic']) ? $_GET['topic'] : [$_GET['topic']],
            fn($topic) => $this->topicUtils->isValidTopicName($topic)
        );
        if(\count($topics) === 0){
            throw new \Error('INVALID_OR_MISSING_TOPIC');
        }
        if(\count(\array_diff($topics, $jwtPayload['mercure']['publish'])) === 0 && \count(\array_diff($jwtPayload['mercure']['publish'], $topics)) === 0){
            throw new \Error('MISSING_TOPIC_AUTHORIZATION');
        }

    }

    public function subscription(){
        extract($_GET);
        if(isset($id) && $id[0] === '#'){
            throw new \Error('INVALID_ID');
        }
        //check content type
        //TODO retrieve topics
        $this->subscriptionManager->setSubscriptionHeaders();
    }

    public function discovery(){
        //TODO https://mercure.rocks/spec#discovery
        /*
         * TODO
         * The cookie SHOULD be set during discovery (see discovery) to improve the overall security. both the publisher and the hub have to share the same second level domain. The Domain attribute MAY be used to allow the publisher and the hub to use different subdomains. See discovery.
         * The cookie SHOULD have the Secure, HttpOnly and SameSite attributes set. The cookie's Path attribute SHOULD also be set to the hub's URL. See security considerations.
         */
    }
}