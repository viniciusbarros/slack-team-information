<?php

use TeamInfo\SlackRetriever;

include_once 'config.php';
include_once 'SlackRetriever.php';
include_once 'vendor/autoload.php';

$team = new SlackRetriever(SLACK_TOKEN);
$info = $team->getUsers();
$placesIndex = array();
$placeIndex = sizeof($places) + 1;


//Preparing Places
if (defined('UNKNOWN_LOCATION_GROUP_NAME') && !isset($places['unknown_location'])) {
    $places['unknown_location'] = array(
        'alias' => array(),
        'displayIndex' => $placeIndex++,
        'name' => UNKNOWN_LOCATION_GROUP_NAME,
    );
}

foreach ($places as $key => $place) {
    $places[$key]['people'] = array();
}

//Looking into each member and checking where it is based on its name
if (isset($info['members'])) {
    $not_found = array();
    foreach ($info['members'] as $pKey=> $person) {

        //Skipping deleted and bot users
        if ($person['deleted'] || $person['is_bot'] || $person['name'] == 'slackbot') {
            continue;
        }

        $found = false;
        $person['final_picture'] = isset($person['profile']['image_' . PROFILE_IMAGE_SIZE]) ? $person['profile']['image_' . PROFILE_IMAGE_SIZE] : 'https://placeholdit.imgix.net/~text?txtsize=17&txt=Profile%20Image&w=' . PROFILE_IMAGE_SIZE . '&h=' . PROFILE_IMAGE_SIZE;

        foreach ($places as $placeKey => $place) {

            foreach ($place['alias'] as $alias) {
                if (strpos(strtolower($person['profile']['real_name_normalized']), strtolower($alias)) !== FALSE) {
                    $places[$placeKey]['people'][] = $person;
                    $found = true;
                    break;
                }
            }
            if ($found) {
                break;
            }
        }

        if (!$found) {
            $not_found[] = $person;
        }
    }

    //Inserting all people w/ unknown location
    $places['unknown_location']['people'] = $not_found;
}

//Displaying
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader);

//Sorting Places according to its displayIndex key
//We sort at the end, to avoid having people "Off" identified as in the "Off"ice
usort($places, function ($a, $b) {
    return $a['displayIndex'] - $b['displayIndex'];
});

$data['places'] = $places;
$twig->display('users.twig', $data);

