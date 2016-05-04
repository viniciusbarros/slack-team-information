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

if (CHECK_DAY_OF_WEEK_IN_NAME && !isset($places['lost_in_time'])) {
    $places['lost_in_time'] = array(
        'alias' => array(),
        'displayIndex' => $placeIndex++,
        'name' => 'Lost in Time (today is ' . date('l') . '!)',
    );
}

foreach ($places as $key => $place) {
    $places[$key]['people'] = array();
}

//Looking into each member and checking where it is based on its name
if (isset($info['members'])) {
    $not_found =
    $lost_in_time = array();
    foreach ($info['members'] as $pKey => $person) {

        //Skipping deleted and bot users
        if ($person['deleted'] || $person['is_bot'] || $person['name'] == 'slackbot') {
            continue;
        }

        $found = false;
        $person['final_picture'] = isset($person['profile']['image_' . PROFILE_IMAGE_SIZE]) ? $person['profile']['image_' . PROFILE_IMAGE_SIZE] : 'https://placeholdit.imgix.net/~text?txtsize=17&txt=Profile%20Image&w=' . PROFILE_IMAGE_SIZE . '&h=' . PROFILE_IMAGE_SIZE;

        foreach ($places as $placeKey => $place) {

            //Checking if person is in one of the setup places
            $pattern = "/(" . strtolower(implode('|', $place['alias'])) . ")/";
            preg_match($pattern, strtolower($person['profile']['real_name_normalized']), $matches);
            if (isset($matches[1]) && !empty($matches[1])) {
                $found = true;

                //checking if person is lost in time
                if (CHECK_DAY_OF_WEEK_IN_NAME) {
                    $pattern = "/(" . strtolower(substr(date('l'), 0, 3)) . ")/";
                    preg_match($pattern, strtolower($person['profile']['real_name_normalized']), $matches);
                }

                if (CHECK_DAY_OF_WEEK_IN_NAME && (!isset($matches[1]) || empty($matches[1]))) {
                    $lost_in_time[] = $person;
                } else {
                    $places[$placeKey]['people'][] = $person;
                }


                break;
            }

        }

        if (!$found) {
            $not_found[] = $person;
        }
    }

    //Inserting all people w/ unknown location
    $places['unknown_location']['people'] = $not_found;

    if(CHECK_DAY_OF_WEEK_IN_NAME){
        //Inserting all lost in time
        $places['lost_in_time']['people'] = $lost_in_time;
    }
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

