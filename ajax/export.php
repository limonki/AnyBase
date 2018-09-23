<?php

require_once("../includes/definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__.'/includes/database.php');
require_once(__ROOT__."/includes/session.php");
require_once(__ROOT__."/includes/backup.php");

$session = new Session();

$backup = new Backup($config["backup_path"], true);
$output_backup = $backup->execute();

$tmp = explode(":", $output_backup);

if($tmp[0] !== true)
{
  if(!empty($tmp[0]) && $tmp[1]) echo $tmp[0];
}

if($tmp[1])
{
  setcookie('title', conv2Send2JS($lang->get('MSG_EXPORT_SUCC_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_EXPORT_SUCC_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo "Succedx01";
}
else
{
  setcookie('title', conv2Send2JS($lang->get('MSG_EXPORT_ERR_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_EXPORT_ERR_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo "Errorx01";
}

?>
