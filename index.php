<?php

use TeamInfo\SlackRetriever;

include_once 'config.php';
include_once 'SlackRetriever.php';
include_once 'vendor/autoload.php';

$team = new SlackRetriever(SLACK_TOKEN);
$info = $team->getUsers();

//Preparing Places
if (defined('UNKNOWN_LOCATION_GROUP_NAME') && !isset($places[UNKNOWN_LOCATION_GROUP_NAME])) {
    $places[UNKNOWN_LOCATION_GROUP_NAME] = array('alias'=>array());
}
foreach ($places as &$place) {
    $place['people'] = array();
}

//Looking into each member and checking where it is based on its name
if (isset($info['members'])) {
    $not_found = array();
    foreach ($info['members'] as &$person) {
        if($person['deleted'] || $person['is_bot'] || $person['name'] == 'slackbot'){
            continue;
        }
        $found = false;
        $person['final_picture'] = isset($person['profile']['image_' . PROFILE_IMAGE_SIZE]) ? $person['profile']['image_' . PROFILE_IMAGE_SIZE] : 'https://placeholdit.imgix.net/~text?txtsize=17&txt=Profile%20Image&w=' . PROFILE_IMAGE_SIZE . '&h=' . PROFILE_IMAGE_SIZE;
        foreach ($places as $key => $place) {
            foreach ($place['alias'] as $alias) {
                if (strpos(strtolower($person['profile']['real_name_normalized']), strtolower($alias)) !== FALSE) {
                    $places[$key]['people'][] = $person;
                    $found = true;
                    break;
                }
            }
        }

        if(!$found){
            $not_found[] = $person;
        }
    }
    $places[UNKNOWN_LOCATION_GROUP_NAME]['people'] = $not_found;
}

//Displaying
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader);

$data['places'] = $places;
$twig->display('users.twig', $data);

