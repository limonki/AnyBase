<?php

require_once('includes/definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();

if(!$session->exist('user')) fireExceptionMsg($lang->get('SESSION_UNAUTHORIZED'), 'session_unauthorized', 'index.php');
else $user = $session->get('user');

if($user->authorized())
{
  if($session->expired()) fireExceptionMsg($lang->get('SESSION_EXPIRED'), 'session_expired', 'index.php');

  $_SESSION[$_POST['key']] = $_POST['value'];
}
else fireExceptionMsg($lang->get('SESSION_UNAUTHORIZED'), 'session_unauthorized', 'index.php');

?>
