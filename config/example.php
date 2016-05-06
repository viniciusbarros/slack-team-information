<?php
//Example of configurations.
//Copy this file and save as config.php

define('PROFILE_IMAGE_SIZE', 48); //24 | 32 | 48 | 72 | 192 | 512
define('SLACK_TOKEN', 'xxxx-99999999999-99999999999-99999999999-99999999999');
define('REQUEST_METHOD','POST'); //can be GET as well
define('CHECK_DAY_OF_WEEK_IN_NAME', false);

$places[] = new TeamInfo\Place('Off', 4, array('oof', 'out of office', 'outofoffice', 'ooo'));
$places[] = new TeamInfo\Place('Office', 1, array('office', 'nch'));
$places[] = new TeamInfo\Place('Working From Home', 3, array('wfh', 'home'));
