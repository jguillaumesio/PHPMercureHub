<?php

namespace Jguillaumesio\PhpMercureHub;

use Rize\UriTemplate;
use UriTemplate\Processor;
use UriTemplate\UriTemplate;

class MecureSubscriptionManager
{
    private $topics = [];
    private $config = [];
    private $request;
    private $utils;
    private $hubUrl;

    public function __construct(){
        $configFilePath = getenv('MERCURE_CONFIG_PATH');
        if($configFilePath === false){
            throw new \Error('MISSING_ENV_MERCURE_CONFIG_PATH');
        }
        if (file_exists($configFilePath) && is_readable($configFilePath)) {
            $this->config = require $configFilePath;
        }
        $this->utils = new $this->config['utils'] ?? new Utils();
        $this->request = [
            'headers' => $this->utils->getHeaders(),
        ];
        $this->processRequest();
        $this->hubUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/.well-known/mercure';
    }

    private function processRequest(){
        $this->request['response_type'] = $this->request['headers']['accept'] ?? $this->request['headers']['content_type'] ?? null;
        $this->request['language'] = $this->request['headers']['accept-language'] ?? null;
    }

    public function getMatchingTopics($selector){
        if($selector === '*'){
            return $this->topics;
        }
        $uri = new UriTemplate($selector, ['version' => 4]);
        return \array_filter($this->topics, fn($topic) => $uri->extract($selector, $topic) !== null || $selector === $topic);
    }

    public function addTopic($topic){
        if(\is_array($this->topics)){
            if(\array_key_exists($topic->name, $this->topics)){
                throw new \Error('TOPIC_ALREADY_EXISTS');
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

    private function setLinkHeaders($resource, $includeSelf = true){
        $headers = [
            ['key' => 'Link', 'value' => "<$this->hubUrl>; rel=\"mercure\""]
        ];
        if($includeSelf){
            $headers[] = ['key' => 'Link', 'value' => '<' . $resource . ($this->request['language'] !== null ? '-'.$this->request['language'] : '') . '.' . $this->request['response_type'] . '>; rel="self"'];
        }
        $this->utils->setHeaders($headers, false);
    }

    private function doesTopicExists($name){
        return \array_key_exists($name, $this->topics);
    }

    public function getTopic($name){
        return $this->doesTopicExists($name) ? $this->topics[$name] : null;
    }
}