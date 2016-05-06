<?php

use TeamInfo\SlackRetriever;

include_once 'classes/SlackRetriever.php';
include_once 'classes/Place.php';
include_once 'classes/Person.php';
include_once 'vendor/autoload.php';


$places = array();
if(file_exists('config/config.php')){
    require_once 'config/config.php';
    $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
    $twig = new Twig_Environment($loader);
}else{
    die('You need to create a <strong>config/config.php</strong> file.<br/>You can duplicate config/example.php for a faster start');
}

$team = new SlackRetriever(SLACK_TOKEN);

if(isset($_GET['channel'])){

    $log = $team->request('channels.history',array('channel' => $_GET['channel']));
    $users_temp = $team->getUsers();
    $users = array();
    foreach($users_temp['members'] as $u){
        $users[$u['id']] = array('profile' => $u['profile']);
    }

    $data['log'] = $log['messages'];
    $data['users'] = $users;
    $data['channel_name'] = isset($_GET['name']) ? $_GET['name'] : '';
    $data['title'] = 'Channel #' . $data['channel_name'];
    $twig->display('channel_log.twig', $data);

}else{
    $channels = $team->request('channels.list');
    $data['channels'] = isset($channels['channels']) ? $channels['channels'] : array();
    $data['link'] = 'channels.php';
    $data['title'] = 'Channels';
    $twig->display('channels.twig', $data);
}


