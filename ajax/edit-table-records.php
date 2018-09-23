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
$user = new User();

if($session->exist('user')) $user = $session->get('user');

$table = htmlentities(str_replace(' ', '', $_POST['table']), ENT_QUOTES);
$columns = array();
$restricted = array();
$str = array();

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
$db->query("SELECT * FROM anybase_restricted");

while($row = $db->fetchAssoc())
{
  array_push($restricted, $row['table_name'].': '.$row['column_name']);
}

$db->query("SHOW COLUMNS FROM ".$config['db_database_prfx'].$table);

$table_edit = new Form();

$table_edit->addElement('div', 'true', null, '', 0, false);
$table_edit->addElement('table', 'true', 1, '', 0, false);
$table_edit->addElement('tr', 'true', 2, '', 0, false);
$table_edit->addElementAttr(1, 'class, style', 'col-18, overflow-x: auto;');
$table_edit->addElementAttr(2, 'id', 'edit-table');

$num = 0;
$i = 3;
while($row = $db->fetchAssoc())
{
  $table_edit->addElement('th', 'true', 3, $row['Field'], 1, false);
  if(strcmp($row['Key'], "PRI") == 0)
  {
    $table_edit->addElementAttr($i+$num+1, 'id, style', 'key, width: 70px; text-align: center;');
    $key = $num;
  }
  array_push($columns, $row['Field']);
  array_push($str, $table.': '.$row['Field']);
  $i++;
  $num++;
}

$db->query("SELECT * FROM ".$config['db_database_prfx'].$table);

while($row = $db->fetchArray())
{
  $table_edit->addElement('tr', 'true', 2, '', 0, false);
  $i++;
  for($j = 0; $j < count($row)/2 + 1; $j++)
  {
    if($j < count($row)/2)
    {
      if(match($str[$j], $restricted))
      {
        $table_edit->addElement('td', 'true', $i, $row[$j], 1, false);
        $table_edit->addElementAttr($i+1, 'class', 'restricted');
      }
      else $table_edit->addElement('td', 'true', $i, $row[$j], 1, false);
      if($key == $j) $table_edit->addElementAttr($i+$j+1, 'id, style', 'key, width: 70px; text-align: center;');
      $i++;
    }
    else
    {
      $table_edit->addElement('td', 'true', $i, '<img id="del-record" style="margin: 0;" src="images/icons/delete16x16.png">', 1, false);
      $table_edit->addElementAttr($i+$j-$num+1, 'class, style', 'delete, width: 30px; text-align: center;');
      $i++;
    }
  }
}

$table_edit->addElement('tr', 'true', 2, '', 0, false);
$table_edit->addElementAttr($i+1, 'id', 'newest');
$i++;
for($j = 0; $j < $num + 1; $j++)
{
  if($j < $num)
  {
    $table_edit->addElement('td', 'true', $i, '<input class="add-table-input col-18" name="'.$columns[$j].'">', 1, false);
    if($key == $j) $table_edit->addElementAttr($i+$j+1, 'id, style', 'key, width: 70px; text-align: center;');
    $i++;
  }
  else
  {
    $table_edit->addElement('td', 'true', $i, '<img id="del-record" style="margin: 0;" src="images/icons/delete16x16.png">', 1, false);
    $table_edit->addElementAttr($i+$j-$num+1, 'class, style', 'delete, width: 30px; text-align: center;');
    $i++;
  }
}

$table_edit->generate();
echo $table_edit->get();

$db->close();

?>
