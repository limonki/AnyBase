<?php

require_once("../includes/definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__."/includes/session.php");

$session = new Session();

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
$query = $db->query("TRUNCATE ".$config['db_database'].".anybase_reg_links");

if($query)
{
  setcookie('title', conv2Send2JS($lang->get('MSG_CLR_REG_LINK_SUCC_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_CLR_REG_LINK_SUCC_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo 'Succedx01';
}
else
{
  setcookie('title', conv2Send2JS($lang->get('MSG_CLR_REG_LINK_ERR_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_CLR_REG_LINK_ERR_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo 'Errorx01';
}

echo $db->error();

$db->close();

?>
