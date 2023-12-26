<?php

namespace Jguillaumesio\PhpMercureHub\Utils;

use Rize\UriTemplate;

class TopicUtils {

    public function getMatchingTopics($selector, $topics){
        if($selector === '*'){
            return $topics;
        }
        $uri = new UriTemplate($selector, ['version' => 4]);
        return \array_filter($topics, fn($topic) => $uri->extract($selector, $topic->name) !== null || $selector === $topic->name);
    }

    public function isValidTopicName($name){
        return \is_string($name) && \strlen($name);
    }

}