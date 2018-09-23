<?php

require_once('includes/definitions.php');
require_once(__ROOT__.'/menu.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/template.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/form.php');
require_once(__ROOT__.'/includes/alert.php');
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

  $alert_error = new Alert();
  $alert_error->addElement('div', 'true', null, '', 0, false);
  $alert_error->addElement('div', 'true', 1, '', 0, false);
  $alert_error->addElement('img', 'false', 2, $lang->get("MSG_LOADING_TBL_FOR_EDIT_ERR"), 1, false);
  $alert_error->addElementAttr(1, 'class', 'alert error');
  $alert_error->addElementAttr(2, 'class', 'alert-danger');
  $alert_error->addElementAttr(3, 'src', 'images/icons/error32x32.png');

  $alert_error->generate();

  $element = array(0 => new PanelElement($lang->get('EDIT_TABLE_PANEL0_HANDY'), $lang->get('EDIT_TABLE_PANEL0_TITLE'), $lang->get('EDIT_TABLE_PANEL0_DESCRIPTION'), $lang->get('EDIT_TABLE_PANEL0_TEXT').'<br><br>'.$alert_error->get().prepareTables2Edit(3), '{ADDITIVE}', '2-sided', array('col' => 18)));

  $element[0]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('EDIT_TABLE_PANEL0_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=Managing - create, edit, delete -all', '', 0, 2, 'all_width', 16);

  elementsOutput();

  $js = new Template('edit-table', 'templates/js/');
  $js->_init();

  $manage = new Template('main', 'templates/');
  $manage->add('SITE.LANGUAGE', $config['language']);
  $manage->add('SITE.TITLE', $lang->get('EDIT_TABLE_SITE_TITLE'));
  $manage->add('SITE.DESCRIPTION', $lang->get('EDIT_TABLE_SITE_DESCRIPTION'));
  $manage->add('SITE.AUTHOR', $lang->get('EDIT_TABLE_SITE_AUTHOR'));
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
