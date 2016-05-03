<?php

define('PROFILE_IMAGE_SIZE', 48); //24 | 32 | 48 | 72 | 192 | 512
define('UNKNOWN_LOCATION_GROUP_NAME', 'Where am I?');
define('SLACK_TOKEN', 'xxxx-99999999999-99999999999-99999999999-99999999999');
define('REQUEST_METHOD','POST'); //can be GET as well

$places = array(
    'off' => array(
        'alias' => array('oof','out of office','outofoffice'),
        'displayIndex' => 3,
        'name' => 'Off'
    ),
    'office' => array(
        'alias' => array('office'),
        'displayIndex' => 1,
        'name' => 'Office'
    ),
    'wfh' => array(
        'alias' => array('wfh', 'home'),
        'displayIndex' => 2,
        'name' => 'Working From Home'

    ),
);
