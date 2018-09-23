<?php

require_once('../includes/definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/database.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();

$default = htmlentities($_POST["default"], ENT_QUOTES);
$username = htmlentities($_POST["username"], ENT_QUOTES);
$firstname = htmlentities($_POST["firstname"], ENT_QUOTES);
$lastname = htmlentities($_POST["lastname"], ENT_QUOTES);
$email = htmlentities($_POST["email"], ENT_QUOTES);
$bio = htmlentities($_POST["bio"], ENT_QUOTES);

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
$db->query("ALTER TABLE anybase_activity_".$default." RENAME TO anybase_activity_".$username);
$query = $db->update('anybase_user', array('username' => $username, 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'bio' => $bio), array('username' => $default));

if($query)
{
  $db->select('anybase_user', '*', array('username' => $username));

  $user_ = $db->fetchAssoc();

  $user = $session->get('user');
  $user->authorize($user_['username'], $user_['password']);

  if($user->authorized())
  {
    $session->insert('user', $user);

    setcookie('title', conv2Send2JS($lang->get('MSG_SUCCESS_TITLE')), 0, '/');
    setcookie('msg', conv2Send2JS($lang->get('MSG_SUCCESS_MSG')), 0, '/');
    setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

    echo 'Succedx01';
  }
  else
  {
    setcookie('title', conv2Send2JS($lang->get('MSG_UNAUTHORIZED_ERROR_TITLE')), 0, '/');
    setcookie('msg', conv2Send2JS($lang->get('MSG_UNAUTHORIZED_ERROR_MSG')), 0, '/');
    setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

    echo 'Errorx01';
  }
}
else
{
  setcookie('title', conv2Send2JS($lang->get('MSG_QUERY_ERROR_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_QUERY_ERROR_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo 'Errorx02';
}

$db->close();

?>
