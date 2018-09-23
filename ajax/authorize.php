<?php

require_once('../includes/definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();

$username = htmlentities($_POST["username"], ENT_QUOTES);
$password = htmlentities($_POST["password"], ENT_QUOTES);

$user = $session->get('user');
$user->authorize($username, url2hash($password));

if($user->authorized())
{
  $session->insert('user', $user);

  setcookie('logged_out', null, -1, '/');

  echo 'Succedx01';
}
else echo 'Errorx01';

?>
