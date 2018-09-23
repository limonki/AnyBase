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

$table = htmlentities(str_replace(' ', '', $_POST['table']), ENT_QUOTES);
$columns = $_POST['columns'];
$update = $_POST['update'];

preg_match_all("/<(.*?)>(.*?)<\/(.*?)>+/i", $columns, $columns);
preg_match_all("/<(.*?)>(.*?)<\/(.*?)>+/i", $update, $update);

for($i = 0; $i < count($columns[1]); $i++)
{
  if(strpos($columns[1][$i], 'key') !== false) $pk = $i;
}

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);

$sql = 'UPDATE '.$config['db_database_prfx'].$table.' SET';
foreach($columns[2] as $key => $value)
{
  if($key != $pk)
  {
    $sql .= ' '.htmlentities($value, ENT_QUOTES).'=\''.htmlentities($update[2][$key], ENT_QUOTES).'\',';
  }
}
$sql .= ' WHERE '.htmlentities($columns[2][$pk], ENT_QUOTES).'='.htmlentities($update[2][$pk], ENT_QUOTES).';';
$sql = str_replace(', WHERE', ' WHERE', $sql);

$query = $db->query($sql);

if($query) echo 'Succedx01';
else echo 'Errorx01';

$db->close();

?>
