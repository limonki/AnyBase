<?php

require_once('includes/definitions.php');
require_once(__ROOT__.'/menu.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/template.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/form.php');
require_once(__ROOT__.'/includes/panel_element.php');
require_once(__ROOT__.'/includes/panel_element_additive.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();
$user = new User();

if($session->exist('user')) $user = $session->get('user');

if($user->authorized())
{
  $session->insert('user', $user);

  setcookie('title-val', conv2Send2JS($lang->get('MSG_LENGTH_EMPTY_TITLE')), 0, '/');
  setcookie('msg-val', conv2Send2JS($lang->get('MSG_LENGTH_EMPTY_MSG')), 0, '/');
  setcookie('close-val', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  if(isSetGet('r')) checkRedirect($_GET['r']);

  $menu->addElementAttr(2, 'class', 'active');
  $menu->addElementAttr(11, 'id', 'fold');

  $user_info = $user->info();

  $create = new Form();

  $create->addElement('div', 'true', null, '', 0, true);
  $create->addElement('form', 'true', 1, '', 0, true);
  $create->addElement('div', 'true', 2, '', 0, false);
  $create->addElement('div', 'true', 3, '', 0, false);
  $create->addElement('input', 'false', 4, '', 0, true);
  $create->addElement('div', 'true', 2, '', 0, false);
  $create->addElement('div', 'true', 6, '', 0, false);
  $create->addElement('div', 'true', 7, '', 0, false);
  $create->addElement('input', 'false', 8, '', 0, true);
  $create->addElement('div', 'true', 7, '', 0, false);
  $create->addElement('select', 'true', 10, '', 0, true);
  $offset_1 = $create->createOption($select_type, 11);
  $create->addElement('div', 'true', 7, '', 0, false);
  $create->addElement('input', 'false', $offset_1+12, '', 0, true);
  $create->addElement('div', 'true', 7, '', 0, false);
  $create->addElement('input', 'false', $offset_1+14, '<div class="center bold">Null</div>', 0, true);
  $create->addElement('div', 'true', 7, '', 0, false);
  $create->addElement('select', 'true', $offset_1+16, '', 0, false);
  $offset_2 = $create->createOption($select_attr, $offset_1+17, 1);
  $create->addElement('div', 'true', 7, '', 0, false);
  $create->addElement('select', 'true', $offset_1+$offset_2+18, '', 0, true);
  $offset_3 = $create->createOption($select_index, $offset_1+$offset_2+19, 1);
  $create->addElement('div', 'true', 7, '', 0, false);
  $create->addElement('input', 'false', $offset_1+$offset_2+$offset_3+20, '<div class="center bold">A_I</div>', 0, true);
  $create->addElement('div', 'true', 7, '', 0, false);
  $create->addElement('a', 'true', $offset_1+$offset_2+$offset_3+22, '', 0, false);
  $create->addElement('img', 'false', $offset_1+$offset_2+$offset_3+23, '', 0, true);
  $create->addElement('div', 'true', 6, '', 0, false);
  $create->addElement('div', 'true', 6, '', 0, false);
  $create->addElement('input', 'false', $offset_1+$offset_2+$offset_3+26, '', 0, false);
  $create->addElement('span', 'true', $offset_1+$offset_2+$offset_3+27, '', 0, false);
  $create->addElement('a', 'true', $offset_1+$offset_2+$offset_3+28, '<div class="center">'.$lang->get('ADD_COLUMNS').'</div>', 1, true);
  $create->addElement('span', 'true', 2, '', 0, true);
  $create->addElement('a', 'true', $offset_1+$offset_2+$offset_3+30, '<div class="center">'.$lang->get('CREATE').'</div>', 1, true);
  $create->addElementAttr(1, 'id', 'create');
  $create->addElementAttr(2, 'method, id', 'POST, basic-form');
  $create->addElementAttr(3, 'class', 'row');
  $create->addElementAttr(4, 'class', 'col-6 separator');
  $create->addElementAttr(5, 'type, class, name, value', 'text, title col-18, table-name, '.$lang->get('TABLE_NAME'));
  $create->addElementAttr(6, 'class', 'row');
  $create->addElementAttr(7, 'class', 'take-column');
  $create->addElementAttr(8, 'class', 'col-3 separator');
  $create->addElementAttr(9, 'type, class, name, value', 'text, title col-18, column-name[], '.$lang->get('COLUMN_NAME'));
  $create->addElementAttr(10, 'class', 'col-3 separator');
  $create->addElementAttr(11, 'type, class, id, name', 'select, title col-18, select-type, type[]');
  $create->addElementAttr($offset_1+12, 'class', 'col-3 separator');
  $create->addElementAttr($offset_1+13, 'type, class, name, value', 'text, title col-18, length[], '.$lang->get('LENGTH'));
  $create->addElementAttr($offset_1+14, 'class', 'col-1 separator');
  $create->addElementAttr($offset_1+15, 'type, class, name, value', 'checkbox, title col-18, null[], true');
  $create->addElementAttr($offset_1+16, 'class', 'col-3 separator');
  $create->addElementAttr($offset_1+17, 'type, class, name', 'select, title col-18, attr[]');
  $create->addElementAttr($offset_1+$offset_2+18, 'class', 'col-3 separator');
  $create->addElementAttr($offset_1+$offset_2+19, 'type, class, name', 'select, title col-18, index[]');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+20, 'class', 'col-1 separator');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+21, 'type, class, name, value', 'checkbox, title col-18, a_i[], true');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+22, 'class, style', 'col-1 separator, visibility: hidden;');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+23, 'href, class', 'return: false;, delete-column');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+24, 'src', 'images/icons/delete20x20.png');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+25, 'class', 'append-columns');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+26, 'class', 'col-18');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+27, 'type, class, name, style, min', 'number, title col-1, num, margin-right: 20px, 0');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+28, 'class', 'set blue');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+29, 'type, class, id', 'submit, btn pri ico, button-add');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+30, 'class', 'set green');
  $create->addElementAttr($offset_1+$offset_2+$offset_3+31, 'type, class, id', 'submit, btn pri ico, button-create');

  $create->generate();

  $element = array(0 => new PanelElement($lang->get('CREATE_TABLE_PANEL0_HANDY'), $lang->get('CREATE_TABLE_PANEL0_TITLE'), $lang->get('CREATE_TABLE_PANEL0_DESCRIPTION'), $lang->get('CREATE_TABLE_PANEL0_TEXT').'<br><br>'.$create->get(), '{ADDITIVE}', '2-sided', array('col' => 18)));

  $element[0]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('CREATE_TABLE_PANEL0_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=Managing - create, edit, delete -all', '', 0, 2, 'all_width', 16);

  elementsOutput();

  $js = new Template('create-table', 'templates/js/');
  $js->_init();

  $manage = new Template('main', 'templates/');
  $manage->add('SITE.LANGUAGE', $config['language']);
  $manage->add('SITE.TITLE', $lang->get('CREATE_TABLE_SITE_TITLE'));
  $manage->add('SITE.DESCRIPTION', $lang->get('CREATE_TABLE_SITE_DESCRIPTION'));
  $manage->add('SITE.AUTHOR', $lang->get('CREATE_TABLE_SITE_AUTHOR'));
  $manage->add('SITE.MENU', $menu->generate());
  $manage->add('USER.INFO', $user_info['firstname'].' '.$user_info['lastname']);
  $manage->add('USER.PERMISSION', convToPermissionStatus($user_info['permission']));
  $manage->add('USER.IMAGE.LOW', $user_info['avatar_low'].'?'.time());
  $manage->add('USER.IMAGE.HIGH', $user_info['avatar_high'].'?'.time());
  $manage->add('PANEL.ELEMENTS', nl2br($elements_output));
  $manage->add('PANEL.FOOT.TEXT', $lang->get('PANEL_FOOT_TEXT'));
  $manage->add('LOGOUT.LINK', 'index.php?session_logout');
  $manage->add('JS.SCRIPTS', $js->output());

  $manage->_init();
  $manage->show();
}
else fireExceptionMsg($lang->get('SESSION_UNAUTHORIZED'), 'session_unauthorized', 'index.php');

?>
