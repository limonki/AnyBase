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

$basic_column = new Form();

$basic_column->addElement('div', 'true', null, '', 0, false);
$basic_column->addElement('div', 'true', 1, '', 0, false);
$basic_column->addElement('div', 'true', 2, '', 0, false);
$basic_column->addElement('input', 'false', 3, '', 0, true);
$basic_column->addElement('div', 'true', 2, '', 0, false);
$basic_column->addElement('select', 'true', 5, '', 0, true);
$offset_1 = $basic_column->createOption($select_type, 6);
$basic_column->addElement('div', 'true', 2, '', 0, false);
$basic_column->addElement('input', 'false', $offset_1+7, '', 0, true);
$basic_column->addElement('div', 'true', 2, '', 0, false);
$basic_column->addElement('input', 'false', $offset_1+9, '<div class="center bold">Null</div>', 0, true);
$basic_column->addElement('div', 'true', 2, '', 0, false);
$basic_column->addElement('select', 'true', $offset_1+11, '', 0, false);
$offset_2 = $basic_column->createOption($select_attr, $offset_1+12, 1);
$basic_column->addElement('div', 'true', 2, '', 0, false);
$basic_column->addElement('select', 'true', $offset_1+$offset_2+13, '', 0, true);
$offset_3 = $basic_column->createOption($select_index, $offset_1+$offset_2+14, 1);
$basic_column->addElement('div', 'true', 2, '', 0, false);
$basic_column->addElement('input', 'false', $offset_1+$offset_2+$offset_3+15, '<div class="center bold">A_I</div>', 0, true);
$basic_column->addElement('div', 'true', 2, '', 0, false);
$basic_column->addElement('a', 'true', $offset_1+$offset_2+$offset_3+17, '', 0, false);
$basic_column->addElement('img', 'false', $offset_1+$offset_2+$offset_3+18, '', 0, true);
$basic_column->addElementAttr(1, 'class', 'row');
$basic_column->addElementAttr(2, 'class', 'append-columns');
$basic_column->addElementAttr(3, 'class', 'col-3 separator');
$basic_column->addElementAttr(4, 'type, class, name, value', 'text, title col-18, column-name[], '.$lang->get('COLUMN_NAME'));
$basic_column->addElementAttr(5, 'class', 'col-3 separator');
$basic_column->addElementAttr(6, 'type, class, id, name', 'select, title col-18, select-type, type[]');
$basic_column->addElementAttr($offset_1+7, 'class', 'col-3 separator');
$basic_column->addElementAttr($offset_1+8, 'type, class, name, value', 'text, title col-18, length[], '.$lang->get('LENGTH'));
$basic_column->addElementAttr($offset_1+9, 'class', 'col-1 separator');
$basic_column->addElementAttr($offset_1+10, 'type, class, name, value', 'checkbox, title col-18, null[], true');
$basic_column->addElementAttr($offset_1+11, 'class', 'col-3 separator');
$basic_column->addElementAttr($offset_1+12, 'type, class, name', 'select, title col-18, attr[]');
$basic_column->addElementAttr($offset_1+$offset_2+13, 'class', 'col-3 separator');
$basic_column->addElementAttr($offset_1+$offset_2+14, 'type, class, name', 'select, title col-18, index[]');
$basic_column->addElementAttr($offset_1+$offset_2+$offset_3+15, 'class', 'col-1 separator');
$basic_column->addElementAttr($offset_1+$offset_2+$offset_3+16, 'type, class, name, value', 'checkbox, title col-18, a_i[], true');
$basic_column->addElementAttr($offset_1+$offset_2+$offset_3+17, 'class', 'col-1 separator');
$basic_column->addElementAttr($offset_1+$offset_2+$offset_3+18, 'href, class', 'return: false;, delete-column');
$basic_column->addElementAttr($offset_1+$offset_2+$offset_3+19, 'src', 'images/icons/delete20x20.png');

$basic_column->generate();

echo $basic_column->get();
echo 'Succedx01';

?>
