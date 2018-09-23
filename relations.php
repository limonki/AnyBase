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

  setcookie('prefix', conv2Send2JS($config['db_database_prfx']), 0, '/');

  $menu->addElementAttr(2, 'class', 'active');
  $menu->addElementAttr(11, 'id', 'fold');

  $user_info = $user->info();

  $add_table = new Form();
  $save_table = new Form();

  $add_table->addElement('span', 'true', null, '', 0, true);
  $add_table->addElement('a', 'true', 1, '<div class="center">'.$lang->get('ADD').'</div>', 1, true);
  $add_table->addElementAttr(1, 'class', 'set green');
  $add_table->addElementAttr(2, 'type, class, id', 'submit, btn pri ico, button-add');

  $add_table->generate();

  $save_table->addElement('span', 'true', null, '', 0, true);
  $save_table->addElement('a', 'true', 1, '<div class="center">'.$lang->get('SAVE').'</div>', 1, true);
  $save_table->addElementAttr(1, 'class', 'set blue');
  $save_table->addElementAttr(2, 'type, class, id', 'submit, btn pri ico, button-save');

  $save_table->generate();

  $js = new Template('relations', 'templates/js/');
  $js->_init();

  $manage = new Template('main', 'templates/');
  $manage->add('SITE.LANGUAGE', $config['language']);
  $manage->add('SITE.TITLE', $lang->get('RELATIONS_SITE_TITLE'));
  $manage->add('SITE.DESCRIPTION', $lang->get('RELATIONS_SITE_DESCRIPTION'));
  $manage->add('SITE.AUTHOR', $lang->get('RELATIONS_SITE_AUTHOR'));
  $manage->add('SITE.MENU', $menu->generate());
  $manage->add('USER.INFO', $user_info['firstname'].' '.$user_info['lastname']);
  $manage->add('USER.PERMISSION', convToPermissionStatus($user_info['permission']));
  $manage->add('USER.IMAGE.LOW', $user_info['avatar_low'].'?'.time());
  $manage->add('USER.IMAGE.HIGH', $user_info['avatar_high'].'?'.time());
  $manage->add('PANEL.ELEMENTS', '<div id="relations">'.$add_table->get().$save_table->get().'</div>');
  $manage->add('PANEL.FOOT.TEXT', $lang->get('PANEL_FOOT_TEXT'));
  $manage->add('LOGOUT.LINK', 'index.php?session_logout');
  $manage->add('JS.SCRIPTS', $js->output());

  $manage->_init();
  $manage->show();
}
else fireExceptionMsg($lang->get('SESSION_UNAUTHORIZED'), 'session_unauthorized', 'index.php');

?>
