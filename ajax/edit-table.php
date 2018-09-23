<?php

require_once('../includes/definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/database.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();

$exists = array();

if(isset($_POST["delete_array"])) $delete_array = $_POST["delete_array"];
if(isset($_POST["after_array"])) $after_array = $_POST["after_array"];
$table_name_def = htmlentities($_POST["table_name_def"], ENT_QUOTES);
$column_name_def = $_POST["column_name_def"];
$length_def = $_POST["length_def"];
$table_name = htmlentities(str_replace(' ', '_', $_POST["table_name"]), ENT_QUOTES);
$column_name = $_POST["column_name"];
$type = $_POST["type"];
$length = $_POST["length"];
if(isset($_POST["null"])) $null = $_POST["null"];
$attr = $_POST["attr"];
$index = $_POST["index"];
if(isset($_POST["a_i"])) $a_i = $_POST["a_i"];

$constraints = array();
$tables = array();
$references = array();
$columns = array();

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);

$db->query("SELECT * FROM information_schema.key_column_usage WHERE constraint_schema = '".$config['db_database']."'");
while($row = $db->fetchAssoc())
{
  if(!strcmp($row['CONSTRAINT_NAME'], "PRIMARY") == 0)
  {
    array_push($constraints, $row['CONSTRAINT_NAME']);
    array_push($tables, $row['TABLE_NAME']);
    array_push($references, $row['REFERENCED_TABLE_NAME']);
    array_push($columns, $row['REFERENCED_COLUMN_NAME']);
  }
}

if(strcmp($table_name_def, $table_name) != 0)
{
  $sql = 'ALTER TABLE '.$config['db_database_prfx'].$table_name_def.' RENAME TO '.$config['db_database_prfx'].$table_name;
  $query = $db->query($sql);
}

$sql = 'ALTER TABLE '.$config['db_database_prfx'].$table_name;
for($i = 0; $i < count($constraints); $i++)
{
  if(strcmp($config['db_database_prfx'].$table_name, $tables[$i]) == 0)
  {
    $sql .= ', DROP FOREIGN KEY '.$constraints[$i].', DROP INDEX '.$constraints[$i];
  }
}
$sql = str_replace($config['db_database_prfx'].$table_name.',', $config['db_database_prfx'].$table_name, $sql);

$db->query($sql);

$sql = '';

$db->query("DESCRIBE ".$config['db_database_prfx'].$table_name);
while($row = $db->fetchArray()) array_push($exists, $row['Field']);

$sql = "ALTER TABLE ".$config['db_database_prfx'].$table_name." ";
$drop_key = "";
$add_key = "";
for($i = 0, $j = 0; $i < count($column_name); $i++)
{
  $column_name_def[$i] = htmlentities($column_name_def[$i], ENT_QUOTES);
  $column_name[$i] = htmlentities($column_name[$i], ENT_QUOTES);
  $type[$i] = htmlentities($type[$i], ENT_QUOTES);
  $length[$i] = htmlentities($length[$i], ENT_QUOTES);
  if(isset($null[$i]) && $null[$i]) $null[$i] = htmlentities($null[$i], ENT_QUOTES);
  if(isset($a_i[$i]) && $a_i[$i]) $a_i[$i] = htmlentities($a_i[$i], ENT_QUOTES);
  $attr[$i] = htmlentities($attr[$i], ENT_QUOTES);
  $index[$i] = htmlentities($index[$i], ENT_QUOTES);

  if(strcmp($column_name[$i], $lang->get('COLUMN_NAME')) != 0)
  {
    $flag = match($column_name_def[$i], $exists);
    $column_name[$i] = str_replace(' ', '_', $column_name[$i]);
    if($flag)
    {
      $sql .= "\nCHANGE COLUMN ".$column_name_def[$i]." ".$column_name[$i]." ".$type[$i];
    }
    else
    {
      $sql .= "\nADD COLUMN ".$column_name[$i]." ".$type[$i];
    }
    if(strcmp($length[$i], $lang->get('LENGTH')) != 0 && !empty($length[$i])) $sql .= "(".$length[$i].")";
    if(isset($null[$i]) && $null[$i]) $sql .= " NULL";
    else $sql .= " NOT NULL";
    if(isset($a_i[$i]) && $a_i[$i]) $sql .= " "."AUTO_INCREMENT";
    if(strcmp($attr[$i], $lang->get('ATTRIBUTES')) != 0) $sql .= " ".$attr[$i];
    if(strcmp($index[$i], $lang->get('INDEX')) != 0)
    {
      $drop_key .= "DROP ".$index[$i]." KEY, ";
      $add_key .= "ADD ".$index[$i]." KEY(".$column_name[$i]."), ";
    }
    if(!$flag)
    {
      $sql .= " AFTER ".$exists[$after_array[$j]['appended']-1];
      $j++;
    }
    if($i < count($column_name) - 1 && strcmp($column_name[$i], $lang->get('COLUMN_NAME')) != 0) $sql .= ",";
  }
}
if(isset($delete_array))
{
  for($i = 0; $i < count($delete_array); $i++)
  {
    $flag = match($delete_array[$i]['name'], $exists);
    if($flag)
    {
      if($i == 0) $sql .= ",\n";
      $sql .= "DROP COLUMN ".$delete_array[$i]['name'];
      if($i < count($delete_array) - 1) $sql .= ",\n";
    }
  }
}
$sql .= ",\n".$drop_key.str_replace(', ', '', $add_key);
$sql .= ";";

$query = $db->query($sql);

$sql = 'ALTER TABLE '.$config['db_database_prfx'].$table_name;
for($i = 0; $i < count($constraints); $i++)
{
  foreach($column_name_def as $key => $value)
  {
    if(strcmp("FK_".$column_name_def[$key], $constraints[$i]) == 0)
    {
      $sql .= ", ADD CONSTRAINT FK_".$column_name[$key]." FOREIGN KEY (".$column_name[$key].") REFERENCES ".$references[$i]." (".$columns[$i].")";
    }
  }
}
$sql = str_replace($config['db_database_prfx'].$table_name.',', $config['db_database_prfx'].$table_name, $sql);

$db->query($sql);

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
