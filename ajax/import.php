<?php

require_once("../includes/definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__.'/includes/database.php');
require_once(__ROOT__."/includes/session.php");
require_once(__ROOT__."/includes/backup.php");

$session = new Session();

$file = $_FILES['file'];

$zip = new ZipArchive;
$flag = false;
$path = '';

if(is_uploaded_file($file['tmp_name']))
{
  $path = $_SERVER['DOCUMENT_ROOT'].'/AnyBase/admin/'.$file['name'];

  if(!empty($file)) move_uploaded_file($file['tmp_name'], $path);
  else move_uploaded_file($file['tmp_name'], $path);
  //header("Location: ".$redirect."&file=".$file['name']);
}

if($zip->open($path) === true)
{
  $zip->extractTo(__ROOT__);
  $zip->close();

  $db = new DataBase($config["db_server"], $config["db_username"], $config["db_password"], $config["db_database"]);

  //$db->query("SET foreign_key_checks = 0");

  if(glob("../*.sql") != false)
  {
    $filecount = count(glob("../*.sql"));
    $arr_file = glob("../*.sql");

    for($i = 0, $j = 0; $i < $filecount; $i++, $j += 3)
    {
      $database_file = file_get_contents($arr_file[$i]);
      $tmp = explode(";", $database_file);
      $data = array();
      foreach($tmp as $key => $value)
      {
        if(!empty($value)) array_push($data, $value);
      }

      $db->restore($data);
      echo $db->error();
    }

    for($i = 0; $i < $filecount; $i++) unlink($arr_file[$i]);

    $flag = unlink($path);
  }

  //$db->query("SET foreign_key_checks = 1");

  $db->close();
}

if($flag)
{
  setcookie('title', conv2Send2JS($lang->get('MSG_IMPORT_SUCC_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_IMPORT_SUCC_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo "Succedx01";
}
else
{
  setcookie('title', conv2Send2JS($lang->get('MSG_IMPORT_ERR_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_IMPORT_ERR_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo "Errorx01";
}

?>
