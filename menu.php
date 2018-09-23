<?php

require_once('includes/definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/menu.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$menu = new Menu(true);

$menu->addElement($lang->get('MENU_1'), 'main.php', 'images/menu/dashboard32x32.png');
$menu->addElement($lang->get('MENU_2'), null, 'images/menu/manage32x32.png');
$menu->addElement($lang->get('MENU_3'), 'manage.php?r='.url2hash('create-table.php'), null, false, 2);
$menu->addElement($lang->get('MENU_4'), 'manage.php?r='.url2hash('edit-table.php'), null, false, 2);
$menu->addElement($lang->get('MENU_5'), 'manage.php?r='.url2hash('delete-table.php'), null, false, 2);
$menu->addElement($lang->get('MENU_6'), 'manage.php?r='.url2hash('relations.php'), null, false, 2);
$menu->addElement($lang->get('MENU_7'), 'tables.php', 'images/menu/tables32x32.png');
$menu->addElement($lang->get('MENU_8'), 'configuration.php', 'images/menu/configuration32x32.png');
$menu->addElement($lang->get('MENU_9'), 'restrict.php', 'images/menu/restrict32x32.png');
$menu->addElement($lang->get('MENU_10'), 'import-export.php', 'images/menu/export32x32.png');
$menu->addElement($lang->get('MENU_11'), null, 'images/menu/fold32x32.png');

?>
