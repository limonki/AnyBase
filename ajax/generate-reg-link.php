<?php

require_once("../includes/definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__."/includes/session.php");

$session = new Session();

$level = explode(" - ", htmlentities($_POST["level"], ENT_QUOTES));
$link = url2hash(generateRandomString());

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
$query = $db->query("INSERT INTO anybase_reg_links(id, link, permission) VALUES (NULL, '".$link."', ".$level[0].")");

if($query)
{
  setcookie('title', conv2Send2JS($lang->get('MSG_REG_LINK_SUCC_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_REG_LINK_SUCC_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  $tmp = explode("htdocs", __ROOT__);
  echo str_replace("\\", "/", $_SERVER['SERVER_NAME'].$tmp[1]."/register.php?v=".$link);
  echo 'Succedx01';
}
else
{
  setcookie('title', conv2Send2JS($lang->get('MSG_REG_LINK_ERR_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_REG_LINK_ERR_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo 'Errorx01';
}

$db->close();

?>
