<?php

require_once("../includes/definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__.'/includes/activity.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/session.php');

$session = new Session();
$user = new User();

if($session->exist('user')) $user = $session->get('user');

$icon = htmlentities($_POST['icon'], ENT_QUOTES);
$key = htmlentities($_POST['key'], ENT_QUOTES);

$user_info = $user->info();

$activity->add($icon, $key, $user_info['username']);

?>
