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

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
$db->query("SHOW TABLES LIKE '".$config['db_database_prfx']."%'");

$select = array();

while($table = $db->fetchArray())
{
  array_push($select, str_replace($config['db_database_prfx'], '', $table[0]));
}

$tables = new Form();

$tables->addElement('form', 'true', null, '', 0, false);
$tables->addElement('select', 'true', 1, '', 0, false);
$tables->createOption($select, 2);
$tables->addElementAttr(1, 'method, id', 'POST, none-form');
$tables->addElementAttr(2, 'type, class, name', 'select, title col-18, table');

$tables->generate();

echo $tables->get();
echo 'Succedx01';

$db->close();

?>
