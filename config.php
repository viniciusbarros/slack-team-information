<?php

define('PROFILE_IMAGE_SIZE', 48); //24 | 32 | 48 | 72 | 192 | 512
define('UNKNOWN_LOCATION_GROUP_NAME', 'Where am I?');
define('SLACK_TOKEN', 'xxxx-99999999999-99999999999-99999999999-99999999999');
define('REQUEST_METHOD','POST'); //can be GET as well

$places = array(
    'Office' => array(
        'alias' => array('office'),
    ),
    'Working From Home' => array(
        'alias' => array('wfh', 'home', 'house'),
    ),
    'Off' => array(
        'alias' => array('off'),
    )
);
