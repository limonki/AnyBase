<?php

require_once("../includes/definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__.'/includes/activity.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/session.php');

$session = new Session();
$user = new User();

if($session->exist('user')) $user = $session->get('user');

setcookie('permission-guest', conv2Send2JS($lang->get('PERMISSION_GUEST')), 0, '/');
echo $user->lvl();

?>
