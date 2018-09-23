<?php

require_once("../includes/definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__.'/includes/activity.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/session.php');

$session = new Session();

if(isset($_POST['restrict'])) $restrict = explode(': ', $_POST['restrict']);
if(isset($_POST['free'])) $free = explode(': ', $_POST['free']);

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);

if(isset($_POST['restrict'])) $sql = "INSERT INTO anybase_restricted(id, table_name, column_name) VALUES (NULL, '".$restrict[0]."', '".$restrict[1]."')";
if(isset($_POST['free'])) $sql = "DELETE FROM anybase_restricted WHERE table_name='".$free[0]."' AND column_name='".$free[1]."'";

$query = $db->query($sql);

if($query)
{
  setcookie('title', conv2Send2JS($lang->get('MSG_SUCCESS_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_SUCCESS_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo 'Succedx01';
}
else
{
  setcookie('title', conv2Send2JS($lang->get('MSG_QUERY_ERROR_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_QUERY_ERROR_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo 'Errorx01';
  echo $db->error();
}

$db->close();

?>
