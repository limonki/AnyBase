<?php

require_once('../includes/definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/database.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();

$username = htmlentities($_POST["username"], ENT_QUOTES);
$pass_old = htmlentities($_POST["pass_old"], ENT_QUOTES);
$pass_new = htmlentities($_POST["pass_new"], ENT_QUOTES);
$pass_confirm = htmlentities($_POST["pass_confirm"], ENT_QUOTES);

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
$db->select('anybase_user', '*', array('username' => $username));

$user_ = $db->fetchAssoc();

if(strcmp($user_['password'], url2hash($pass_old)) == 0)
{
  if(strcmp($pass_new, $pass_confirm) == 0)
  {
    $query = $db->update('anybase_user', array('password' => url2hash($pass_new)), array('username' => $username));

    if($query)
    {
      $db->select('anybase_user', '*', array('username' => $username));

      $user_ = $db->fetchAssoc();

      $user = $session->get('user');
      $user->authorize($user_['username'], $user_['password']);

      if($user->authorized())
      {
        $session->insert('user', $user);

        setcookie('title', conv2Send2JS($lang->get('MSG_PASS_SUCCESS_TITLE')), 0, '/');
        setcookie('msg', conv2Send2JS($lang->get('MSG_PASS_SUCCESS_MSG')), 0, '/');
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
  }
  else
  {
    setcookie('title', conv2Send2JS($lang->get('MSG_NEW_PASS_INC_ERROR_TITLE')), 0, '/');
    setcookie('msg', conv2Send2JS($lang->get('MSG_NEW_PASS_INC_ERROR_MSG')), 0, '/');
    setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

    echo 'Errorx03';
  }
}
else
{
  setcookie('title', conv2Send2JS($lang->get('MSG_OLD_PASS_INC_ERROR_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_OLD_PASS_INC_ERROR_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo 'Errorx04';
}

$db->close();

?>
