<?php

use TeamInfo\SlackRetriever;

include_once 'classes/SlackRetriever.php';
include_once 'classes/Place.php';
include_once 'classes/Person.php';
include_once 'vendor/autoload.php';


$places = array();
if(file_exists('config/config.php')){
    require_once 'config/config.php';
}else{
    die('You need to create a <strong>config/config.php</strong> file.<br/>You can duplicate config/example.php for a faster start');
}


$placeNotFound = new Place('Where am I?', 5, array(), false);
$placeLost = new Place('Lost in time? Today is ' . date('l') . '', 6, array(), false);

$places[] = &$placeNotFound;
$places[] = &$placeLost;

$team = new SlackRetriever(SLACK_TOKEN);
$info = $team->getUsers();

if (isset($info['members'])) {

    foreach ($info['members'] as $user_info) {

        //Skipping deleted and bot users
        if ($user_info['deleted'] || $user_info['is_bot'] || $user_info['name'] == 'slackbot') {
            continue;
        }

        $person = new Person($user_info);
        $found = false;

        foreach ($places as $place) {

            if (!$place->isSearchable()) {
                continue;
            }

            if (!$found && $place->checkPerson($person->getProfileAttr('real_name_normalized'))) {
                $found = true;
                $lost = false;
                //checking if person is lost in time
                if (CHECK_DAY_OF_WEEK_IN_NAME) {
                    $pattern = "/(" . strtolower(substr(date('l'), 0, 3)) . ")/";
                    preg_match($pattern, strtolower($person->getProfileAttr('real_name_normalized')), $matches);
                    if (!isset($matches[1]) || empty($matches[1])) {
                        $placeLost->addPerson($person);
                        $lost = true;
                    }
                }

                if (!$lost) {
                    $place->addPerson($person);
                }
            }
        }

        if (!$found) {
            $placeNotFound->addPerson($person);
        }
    }
}

//Displaying
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader);

//Sorting Places according to its displayIndex key
//We sort at the end, to avoid having people "Off" identified as in the "Off"ice
usort($places, function ($a, $b) {
    return $a->getDisplayIndex() - $b->getDisplayIndex();
});

$data['places'] = $places;
$twig->display('users.twig', $data);

