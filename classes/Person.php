<?php

namespace TeamInfo;

/**
 * User: Vinicius
 * Date: 04/05/2016
 */
class Person
{
    private $slackInfo = array();

    public function __construct($slackInfo)
    {
        $this->slackInfo = $slackInfo;
    }

    public function getPicture($size)
    {
        return isset($this->slackInfo['profile']['image_' . $size])
            ? $this->slackInfo['profile']['image_' . $size]
            : 'https://placeholdit.imgix.net/~text?txtsize=17&txt=Profile%20Image&w=' . $size . '&h=' . $size;
    }

    public function getProfileAttr($attr=false){
        $return = false;
        if($attr && isset($this->slackInfo['profile'][$attr])){
            $return = $this->slackInfo['profile'][$attr];
        }
        return $return;
    }

}