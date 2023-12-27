<?php

namespace Jguillaumesio\PhpMercureHub\Utils;

use Rize\UriTemplate;

class TopicUtils {

    public static function getMatchingTopics($selectors, $topics){
        if(\in_array('*', $selectors)){
            return $topics;
        }
        $result = [];
        foreach ($selectors as $selector) {
            $uri = new UriTemplate($selector, ['version' => 4]);
            $matchingTopics = array_filter($topics, function ($topic) use ($uri, $selector, $result) {
                return !\in_array($topic->name, $result) && ($uri->extract($selector, $topic->name) !== null || $selector === $topic->name);
            });
            $result = array_merge($result, $matchingTopics);
        }
        return $result;
    }

    public static function getMatchingTopic($selectors, $topics){
        if(\in_array('*', $selectors)){
            return $topics[0];
        }
        foreach ($selectors as $selector) {
            $uri = new UriTemplate($selector, ['version' => 4]);
            foreach($topics as $topic){
                if($uri->extract($selector, $topic->name) !== null || $selector === $topic->name){
                    return $topic;
                }
            }
        }
        return null;
    }

    public static function isValidTopicName($name){
        return \is_string($name) && \strlen($name);
    }

}