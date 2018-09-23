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

$selected = $_POST['selected'];

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
$db->query("SHOW CREATE TABLE ".$config['db_database_prfx'].$selected);
$create = $db->fetchArray()[1];

preg_match_all("/FOREIGN KEY \(`(.*?)`\)+/i", $create, $foreign_key);
preg_match_all("/REFERENCES `(.*?)` \(`(.*?)`\)+/i", $create, $references);

foreach($references as $key => $value)
{
  $references[$key] = str_replace($config['db_database_prfx'], '', $value);
}

$db->query("DESCRIBE ".$config['db_database_prfx'].$selected);

$table = new Form();

$table->addElement('div', 'true', null, '', 0, false);
$table->addElement('div', 'true', 1, $selected, 1, false);
$table->addElement('table', 'true', 1, '', 0, false);
$table->addElementAttr(1, 'class', 'col-18');
$table->addElementAttr(2, 'class', 'table-name');
$table->addElementAttr(3, 'id', $selected);

$i = 2;
while($row = $db->fetchAssoc())
{
  $table->addElement('tr', 'true', 3, '', 0, false);
  if(strcmp($row['Key'], 'PRI') == 0) $table->addElement('td', 'true', $i+2, '<span style="display: none;">PRI</span><img style="margin: 0;" src="images/icons/pri16x16.png">', 1, false);
  else if(strcmp($row['Key'], 'MUL') == 0) $table->addElement('td', 'true', $i+2, '<span style="display: none;">MUL</span><img style="margin: 0;" src="images/icons/for16x16.png">', 1, false);
  else $table->addElement('td', 'true', $i+2, $row['Key'], 1, false);
  $table->addElement('td', 'true', $i+2, $row['Field'], 1, false);
  if(strcmp($row['Key'], 'PRI') == 0) $table->addElementAttr($i+4, 'id', 'p-'.$row['Field']);
  else if(strcmp($row['Key'], 'MUL') == 0) $table->addElementAttr($i+4, 'id', 'f-'.$row['Field']);
  else $table->addElementAttr($i+4, 'id', $row['Field']);
  $i+=3;
}

$table->generate();

echo $table->get();
echo "FOREIGN KEY:".json_encode($foreign_key[1])."END";
echo "REFERENCES:".json_encode($references)."END";
echo 'Succedx01';

$db->close();

?>
