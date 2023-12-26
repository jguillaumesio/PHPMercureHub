<?php

namespace Jguillaumesio\PhpMercureHub;

use Jguillaumesio\PhpMercureHub\Authorization\AuthorizationManager;
use Jguillaumesio\PhpMercureHub\Models\Subscriber;
use Jguillaumesio\PhpMercureHub\Models\Topic;
use Jguillaumesio\PhpMercureHub\Utils\TopicUtils;
use Jguillaumesio\PhpMercureHub\Utils\Utils;

class SubscriptionManager
{
    private $topics = [];
    private $subscribers = [];
    private $request;
    private $utils;
    private $hubUrl;
    private $topicUtils;

    public function getTopics() {
        return $this->topics;
    }

    public function setTopics(array $topics) {
        $this->topics = $topics;
    }

    public function getRequest() {
        return $this->request;
    }

    public function setRequest(array $request) {
        $this->request = $request;
    }

    public function getUtils() {
        return $this->utils;
    }

    public function setUtils($utils) {
        $this->utils = $utils;
    }

    public function getHubUrl() {
        return $this->hubUrl;
    }

    public function setHubUrl(string $hubUrl) {
        $this->hubUrl = $hubUrl;
    }

    public function __construct(){
        $config = Config::getConfig();
        $this->utils = new $config['utils'] ?? new Utils();
        $this->request = [
            'headers' => $this->utils->getHeaders(),
            'query_params' => $this->utils->getQueryParams(),
            'cookies' => $this->utils->getCookies()
        ];
        $this->processRequest();
        $this->hubUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/.well-known/mercure';
        $this->topicUtils = new TopicUtils();
    }

    private function processRequest(){
        $this->request['response_type'] = $this->request['headers']['accept'] ?? $this->request['headers']['content_type'] ?? null;
        $this->request['language'] = $this->request['headers']['accept-language'] ?? null;
    }

    public function addTopic($topicName){
        if(\is_array($this->topics)){
            $topic = new Topic($topicName);
            if(\array_key_exists($topicName, $this->topics)){
                throw new \Error('TOPIC_ALREADY_EXISTS');
            }
            $this->topics[$topicName] = $topic;
        }
    }

    private function getSubscriber($id){
        if (array_key_exists($id, $this->subscribers)) {
            return $this->subscribers[$id];
        } else {
            return null;
        }
    }

    public function subscribe($selector){
        $topics = $this->topicUtils->getMatchingTopics($selector, $this->topics);
        if(\count($topics) > 0){
            $jwtPayload = (new AuthorizationManager())->getJWTPayload($this->request);
            $subscriber = $this->getSubscriber($jwtPayload['subscriber'] ?? null);
            if($subscriber === null){
                $this->subscribers[] = new Subscriber($topics);
            } else {
                $subscriber->subscribe($topics);
            }
            return true;
        }
        return false;
    }

    private function setResponseTypeHeader(){
        if($this->request['response_type'] === null){
            throw new \Error('MISSING_CONTENT_TYPE_OR_RESPONSE_TYPE');
        }
        else if(!array_key_exists($this->request['response_type'], $this->utils::$availableResponseTypes)) {
            throw new \Error('INVALID_CONTENT_TYPE_OR_RESPONSE_TYPE');
        }
        $this->utils->setHeader('Content-type', $this->request['response_type']);
    }

    private function setLinkHeaders($topic, $includeSelf = true){
        $headers = [
            ['key' => 'Link', 'value' => "<$this->hubUrl>; rel=\"mercure\""]
        ];
        if($includeSelf){
            $headers[] = ['key' => 'Link', 'value' => '<' . $topic . ($this->request['language'] !== null ? '-'.$this->request['language'] : '') . '.' . $this->request['response_type'] . '>; rel="self"'];
        }
        $this->utils->setHeaders($headers, false);
    }

    public function setSubscriptionHeaders($topic){
        $this->setLinkHeaders($topic);
        $this->setResponseTypeHeader();
    }

    public function setPublicationHeaders($topic){
        $this->setLinkHeaders($topic);
        $this->setResponseTypeHeader();
    }

    public function getAllSubscriptions(){
        return \array_reduce($this->topics, fn($acc, $topic) => [...$acc, ...$topic->getSubscriptions()], []);
    }

    public function getSubscriptionByTopicSelector($selector){
        $topics = $this->topicUtils->getMatchingTopics($selector, $this->topics);
        return \array_reduce($topics, fn($acc, $topic) => [...$acc, ...$topic->getSubscriptions()], []);
    }

    public function getSubscriptionForTopic($topicName, $subscriberId){
        $subscriber = $this->getSubscriber($subscriberId);
        $topics = $this->topicUtils->getMatchingTopics($topicName, $this->topics);
        if(\count($topics) !== 1 || $subscriber === null){
            return null;
        }
        return $topics[0]->getSubscription($subscriber);
    }
}