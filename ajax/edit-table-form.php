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

$table_name = htmlentities($_POST['table_name'], ENT_QUOTES);

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
$query = $db->query("DESCRIBE ".$table_name);

$table_name = str_replace($config['db_database_prfx'], '', $table_name);

if($query)
{
  $name = new Form();
  $main_body = new Form();
  $buttons = new Form();

  $name->addElement('div', 'true', null, '', 0, false);
  $name->addElement('div', 'true', 1, '', 0, false);
  $name->addElement('input', 'false', 2, '', 0, false);
  $name->addElementAttr(1, 'class', 'row');
  $name->addElementAttr(2, 'class', 'col-6 separator');
  $name->addElementAttr(3, 'type, class, name, value', 'text, title col-18, table-name, '.$table_name);

  $name->generate();

  $main_body->addElement('div', 'true', null, '', 0, false);
  $main_body->addElement('div', 'true', 1, '', 0, false);
  $main_body->addElement('input', 'false', 2, '', 0, true);
  $main_body->addElement('div', 'true', 1, '', 0, false);
  $main_body->addElement('select', 'true', 4, '', 0, true);
  $offset_1 = $main_body->createOption($select_type, 5);
  $main_body->addElement('div', 'true', 1, '', 0, false);
  $main_body->addElement('input', 'false', $offset_1+6, '', 0, true);
  $main_body->addElement('div', 'true', 1, '', 0, false);
  $main_body->addElement('input', 'false', $offset_1+8, '<div class="center bold">Null</div>', 0, true);
  $main_body->addElement('div', 'true', 1, '', 0, false);
  $main_body->addElement('select', 'true', $offset_1+10, '', 0, false);
  $offset_2 = $main_body->createOption($select_attr, $offset_1+11, 1);
  $main_body->addElement('div', 'true', 1, '', 0, false);
  $main_body->addElement('select', 'true', $offset_1+$offset_2+12, '', 0, true);
  $offset_3 = $main_body->createOption($select_index, $offset_1+$offset_2+13, 1);
  $main_body->addElement('div', 'true', 1, '', 0, false);
  $main_body->addElement('input', 'false', $offset_1+$offset_2+$offset_3+14, '<div class="center bold">A_I</div>', 0, true);
  $main_body->addElement('div', 'true', 1, '', 0, false);
  $main_body->addElement('a', 'true', $offset_1+$offset_2+$offset_3+16, '', 0, false);
  $main_body->addElement('img', 'false', $offset_1+$offset_2+$offset_3+17, '', 0, true);
  $main_body->addElementAttr($offset_1+$offset_2+$offset_3+16, 'class', 'col-1 separator');
  $main_body->addElementAttr($offset_1+$offset_2+$offset_3+17, 'href, class', 'return: false;, delete-column');
  $main_body->addElementAttr($offset_1+$offset_2+$offset_3+18, 'src', 'images/icons/delete20x20.png');

  $columns = array();
  $j = 1;

  while($row = $db->fetchAssoc())
  {
    $foreign = false;
    $primary = false;

    array_push($columns, $row['Field']);

    if(strcmp($row['Key'], 'MUL') == 0) $foreign = true;
    if(strcmp($row['Key'], 'PRI') == 0) $primary = true;

      // [Field] => a [Type] => int(11) [Null] => NO [Key] => [Default] => [Extra] =>
    $s = strpos($row['Type'], '(')+1;
    $e = strpos($row['Type'], ')')-3;
    $type = explode('(', $row['Type']);
    $attr = explode(') ', $row['Type']);
    if($foreign)  $main_body->addElementAttr(1, 'class, id', 'row foreign, '.$j);
    else if($primary)  $main_body->addElementAttr(1, 'class, id', 'row primary, '.$j);
    else $main_body->addElementAttr(1, 'class, id', 'row, '.$j);
    $main_body->addElementAttr(2, 'class', 'col-3 separator');
    $main_body->addElementAttr(3, 'type, class, name, value', 'text, title col-18, column-name[], '.$row['Field']);
    $main_body->addElementAttr(4, 'class', 'col-3 separator');
    $main_body->addElementAttr(5, 'type, class, id, name', 'select, title col-18, select-type, type[]');
    for($i = 6; $i < $offset_1+6; $i++)
    {
      $main_body->delElementAttr($i, 'selected, style');
      if(strcmp($main_body->elementAdditionalText($i), strtoupper($type[0])) == 0) $main_body->addElementAttr($i, 'selected, style', ', font-weight: bold');
    }
    $main_body->addElementAttr($offset_1+6, 'class', 'col-3 separator');
    $main_body->addElementAttr($offset_1+7, 'type, class, name, value', 'text, title col-18, length[], '.str_replace(')', '', substr($row['Type'], $s, $e)));
    $main_body->addElementAttr($offset_1+8, 'class', 'col-1 separator');
    if(strcmp($row['Null'], 'NO') == 0)
    {
      $main_body->delElementAttr($offset_1+9, 'checked');
      $main_body->addElementAttr($offset_1+9, 'type, class, name, value', 'checkbox, title col-18, null[], true');
    }
    else $main_body->addElementAttr($offset_1+9, 'type, class, name, value, checked', 'checkbox, title col-18, null[], true, ');
    $main_body->addElementAttr($offset_1+10, 'class', 'col-3 separator');
    $main_body->addElementAttr($offset_1+11, 'type, class, name', 'select, title col-18, attr[]');
    for($i = $offset_1+12; $i < $offset_1+$offset_2+12; $i++)
    {
      $main_body->delElementAttr($i, 'selected, style');
      if(!empty($attr[1]))
      {
        if(strpos($main_body->elementAdditionalText($i), strtoupper($attr[1])) !== false) $main_body->addElementAttr($i, 'selected, style', ', font-weight: bold');
      }
    }
    $main_body->addElementAttr($offset_1+$offset_2+12, 'class', 'col-3 separator');
    $main_body->addElementAttr($offset_1+$offset_2+13, 'type, class, name', 'select, title col-18, index[]');
    for($i = $offset_1+$offset_2+14; $i < $offset_1+$offset_2+$offset_3+14; $i++)
    {
      $main_body->delElementAttr($i, 'selected, style');
      if(!empty($row['Key']))
      {
        if(strpos($main_body->elementAdditionalText($i), $row['Key']) !== false) $main_body->addElementAttr($i, 'selected, style', ', font-weight: bold');
      }
    }
    $main_body->addElementAttr($offset_1+$offset_2+$offset_3+14, 'class', 'col-1 separator');
    if(strcmp($row['Extra'], 'auto_increment') == 0) $main_body->addElementAttr($offset_1+$offset_2+$offset_3+15, 'type, class, name, value, checked', 'checkbox, title col-18, a_i[], true, ');
    else
    {
      $main_body->delElementAttr($offset_1+$offset_2+$offset_3+15, 'checked');
      $main_body->addElementAttr($offset_1+$offset_2+$offset_3+15, 'type, class, name, value', 'checkbox, title col-18, a_i[], true');
    }

    $main_body->generate();

    $j++;
  }

  $buttons->addElement('div', 'true', null, '', 0, false);
  $buttons->addElement('input', 'false', 1, '', 0, false);
  $buttons->addElement('span', 'true', 2, '', 0, false);
  $buttons->addElement('a', 'true', 3, '<div class="center">'.$lang->get('ADD_COLUMNS_AFTER').'</div>', 1, false);
  $buttons->addElement('select', 'true', 1, '', 0, true);
  $offset_1 = $buttons->createOption($columns, 5);
  $buttons->addElement('div', 'true', 1, '', 0, false);
  $buttons->addElement('span', 'true', $offset_1+6, '', 0, true);
  $buttons->addElement('a', 'true', $offset_1+7, '<div class="center">'.$lang->get('SAVE').'</div>', 1, true);
  $buttons->addElementAttr(1, 'class', 'col-18');
  $buttons->addElementAttr(2, 'type, class, name, style, min', 'number, title col-1, num, margin-right: 20px, 0');
  $buttons->addElementAttr(3, 'class', 'set blue');
  $buttons->addElementAttr(4, 'type, class, id', 'submit, btn pri ico, button-add');
  $buttons->addElementAttr(5, 'type, class, name', 'select, title, add-after');
  $buttons->addElementAttr($offset_1+6, 'class', 'col-18');
  $buttons->addElementAttr($offset_1+7, 'class', 'set green');
  $buttons->addElementAttr($offset_1+8, 'type, class, id', 'submit, btn pri ico, button-edit');

  $buttons->generate();

  echo '<form>'.$name->get().$main_body->get().$buttons->get().'</form>/s/';
  echo 'Succedx01';
}
else
{
  echo '/s/';
  echo 'Errorx01';
}

$db->close();

?>
