<?php

require_once('includes/definitions.php');
require_once(__ROOT__.'/menu.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/template.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/panel_element.php');
require_once(__ROOT__.'/includes/panel_element_additive.php');
require_once(__ROOT__.'/includes/form.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();
$user = new User();

if($session->exist('user')) $user = $session->get('user');

if($user->authorized())
{
  setcookie('restricted', conv2Send2JS($lang->get('RESTRICTED')), 0, '/');
  setcookie('record-not-added', conv2Send2JS($lang->get('RECORD_NOT_ADDED')), 0, '/');
  setcookie('record-not-updated', conv2Send2JS($lang->get('RECORD_NOT_UPDATED')), 0, '/');
  setcookie('record-not-deleted', conv2Send2JS($lang->get('RECORD_NOT_DELETED')), 0, '/');
  setcookie('record-success', conv2Send2JS($lang->get('RECORD_SUCCESS')), 0, '/');

  $session->insert('user', $user);

  $menu->addElementAttr(7, 'class', 'active');
  $menu->addElementAttr(11, 'id', 'fold');

  $user_info = $user->info();

  $element = array(0 => new PanelElement($lang->get('TABLES_PANEL0_HANDY'), $lang->get('TABLES_PANEL0_TITLE'), $lang->get('TABLES_PANEL0_DESCRIPTION'), $lang->get('TABLES_PANEL0_TEXT').'<br><br>'.prepareTables2Edit(3), '{ADDITIVE}', '2-sided', array('col' => 18)));

  $element[0]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('TABLES_PANEL0_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=Tables- add, delete, update rows -all', '', 0, 2, 'all_width', 16);

  elementsOutput();

  $js = new Template('tables', 'templates/js/');
  $js->_init();

  $main = new Template('main', 'templates/');
  $main->add('SITE.LANGUAGE', $config['language']);
  $main->add('SITE.TITLE', $lang->get('TABLES_SITE_TITLE'));
  $main->add('SITE.DESCRIPTION', $lang->get('TABLES_SITE_DESCRIPTION'));
  $main->add('SITE.AUTHOR', $lang->get('TABLES_SITE_AUTHOR'));
  $main->add('SITE.MENU', $menu->generate());
  $main->add('USER.INFO', $user_info['firstname'].' '.$user_info['lastname']);
  $main->add('USER.PERMISSION', convToPermissionStatus($user_info['permission']));
  $main->add('USER.IMAGE.LOW', $user_info['avatar_low'].'?'.time());
  $main->add('USER.IMAGE.HIGH', $user_info['avatar_high'].'?'.time());
  $main->add('PANEL.ELEMENTS', nl2br($elements_output));
  $main->add('PANEL.FOOT.TEXT', $lang->get('PANEL_FOOT_TEXT'));
  $main->add('LOGOUT.LINK', 'index.php?session_logout');
  $main->add('JS.SCRIPTS', $js->output());

  $main->_init();
  $main->show();
}
else fireExceptionMsg($lang->get('SESSION_UNAUTHORIZED'), 'session_unauthorized', 'index.php');

?>
