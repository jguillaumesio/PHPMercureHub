<?php

namespace Jguillaumesio\PhpMercureHub;

use Jguillaumesio\PhpMercureHub\Models\Subscriber;
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

    public function addTopic($topic){
        if(\is_array($this->topics)){
            if(\array_key_exists($topic->name, $this->topics)){
                throw new \Error('TOPIC_ALREADY_EXISTS');
            }
            if(!$this->topicUtils->isValidTopicName($topic->name)){
                throw new \Error('INVALID_EXISTS');
            }
            $this->topics[$topic->name] = null;
        }
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
        //.well-known/mercure/subscriptions: the collection of subscriptions
        return \array_reduce($this->topics, fn($acc, $topic) => [...$acc, ...$topic->subscribers], []);
    }

    public function getSubscriptionByTopicSelector($selector){
        //.well-known/mercure/subscriptions/{topic}: the collection of subscriptions for the given topic selector
        $topics = $this->topicUtils->getMatchingTopics($selector, $this->topics);
        return \array_reduce($topics, fn($acc, $topic) => [...$acc, ...$topic->subscribers], []);
    }

    public function getSubscriptionForTopic($subscriber){
        //.well-known/mercure/subscriptions/{topic}/{subscriber}: a specific subscription
        $topics = $this->topicUtils->getMatchingTopics($selector, $this->topics);
        return \array_reduce($topics, fn($acc, $topic) => [...$acc, ...$topic->subscribers], []);
    }
}