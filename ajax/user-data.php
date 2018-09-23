<?php

require_once('../includes/definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/database.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/form.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();

if($session->exist('user')) $user_info = $session->get('user')->info();

$user = htmlentities($_POST["user"], ENT_QUOTES);

if(!strcmp($user, '---') == 0)
{
  $db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
  $db->query("SELECT * FROM anybase_user WHERE username='".$user."'");
  $data = $db->fetchAssoc();

  $exclude = array("id", "username", "password", "avatar", "bio", "permission");

  $user_data = new Form();
  $user_data->addElement("div", "true", null);
  $user_data->addElementAttr(1, "class", "row");
  $user_data->addElement("form", "true", 1);
  $user_data->addElementAttr(2, "id", "user-info");
  $i = 3;
  foreach($data as $key => $value)
  {
    if(!match($key, $exclude))
    {
      $user_data->addElement("input", "false", 2, "", 0, false);
      $user_data->addElementAttr($i, "class, name, value", "title col-18, $key, $value");
      $i++;
    }
    else if(strcmp($key, "bio") == 0)
    {
      $user_data->addElement("textarea", "true", 2, $value, 1, false);
      $user_data->addElementAttr($i, "class, name", "title col-18, $key");
      $i++;
    }
    else if(strcmp($key, "permission") == 0)
    {
      $user_data->addElement("select", "true", 1, "", 0, false);
      $user_data->addElementAttr($i, "class, name", "title, $key");
      $offset = $user_data->createOption($permission_lvl, $i, $value);
      $i++;
    }
  }
  $user_data->generate();

  if($user_info['permission'] > $value) echo $user_data->get();
  else echo 'Location: ../401-restricted.php';

  $db->close();
}

?>
