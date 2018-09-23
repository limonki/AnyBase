<?php

require_once('../includes/definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/database.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();

$table_name = htmlentities(str_replace(' ', '_', $_POST["table_name"]), ENT_QUOTES);
$column_name = $_POST["column_name"];
$type = $_POST["type"];
$length = $_POST["length"];
if(isset($_POST["null"])) $null = $_POST["null"];
$attr = $_POST["attr"];
$index = $_POST["index"];
if(isset($_POST["a_i"])) $a_i = $_POST["a_i"];

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);

$sql = '';

if(strcmp($table_name, $lang->get('TABLE_NAME')) != 0)
{
  $sql = "CREATE TABLE ".$config['db_database_prfx'].$table_name." (";
  for($i = 0; $i < count($column_name); $i++)
  {
    $column_name[$i] = htmlentities($column_name[$i], ENT_QUOTES);
    $type[$i] = htmlentities($type[$i], ENT_QUOTES);
    $length[$i] = htmlentities($length[$i], ENT_QUOTES);
    if(isset($_POST["null"])) $null[$i] = htmlentities($null[$i], ENT_QUOTES);
    $attr[$i] = htmlentities($attr[$i], ENT_QUOTES);
    $index[$i] = htmlentities($index[$i], ENT_QUOTES);
    if(isset($_POST["a_i"])) $a_i[$i] = htmlentities($a_i[$i], ENT_QUOTES);

    if(strcmp($column_name[$i], $lang->get('COLUMN_NAME')) != 0)
    {
      $sql .= str_replace(' ', '_', $column_name[$i])." ".$type[$i];
      if(strcmp($length[$i], $lang->get('LENGTH')) != 0) $sql .= "(".$length[$i].")";
      if(strcmp($attr[$i], $lang->get('ATTRIBUTES')) != 0) $sql .= " ".$attr[$i];
      if(isset($a_i[$i]) && $a_i[$i]) $sql .= " "."AUTO_INCREMENT";
      if(strcmp($index[$i], $lang->get('INDEX')) != 0) $sql .= " ".$index[$i]." KEY";
      if(isset($null[$i]) && $null[$i]) $sql .= " NULL";
      else $sql .= " NOT NULL";
      if($i < count($column_name) - 1 && strcmp($column_name[$i+1], $lang->get('COLUMN_NAME')) != 0) $sql .= ",\n";
    }
  }
  $sql .= ") ENGINE = InnoDB;";
}

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
