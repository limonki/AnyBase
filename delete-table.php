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

  if(isSetGet('r')) checkRedirect($_GET['r']);

  $menu->addElementAttr(2, 'class', 'active');
  $menu->addElementAttr(11, 'id', 'fold');

  $user_info = $user->info();

  $alert_warning = new Alert();
  $alert_warning->addElement('div', 'true', null, '', 0, false);
  $alert_warning->addElement('div', 'true', 1, '', 0, false);
  $alert_warning->addElement('img', 'false', 2, $lang->get("MSG_CNFRM_RMV"), 1, false);
  $alert_warning->addElement('div', 'true', 1, '', 0, false);
  $alert_warning->addElement('div', 'true', 4, '', 0, false);
  $alert_warning->addElement('div', 'true', 5, '', 0, false);
  $alert_warning->addElement('a', 'true', 6, '', 0, false);
  $alert_warning->addElement('div', 'true', 7, $lang->get("DISCARD"), 1, false);
  $alert_warning->addElement('div', 'true', 4, '', 0, false);
  $alert_warning->addElement('div', 'true', 9, '', 0, false);
  $alert_warning->addElement('a', 'true', 10, '', 0, false);
  $alert_warning->addElement('div', 'true', 11, $lang->get("CONFIRM"), 1, false);
  $alert_warning->addElementAttr(1, 'class', 'alert confirm');
  $alert_warning->addElementAttr(2, 'class', 'alert-warning');
  $alert_warning->addElementAttr(3, 'src', 'images/icons/alert32x32.png');
  $alert_warning->addElementAttr(4, 'class', 'center');
  $alert_warning->addElementAttr(5, 'class', 'inline');
  $alert_warning->addElementAttr(6, 'class', 'set green');
  $alert_warning->addElementAttr(7, 'href, class, id', 'return: false;, btn sec ico, discard');
  $alert_warning->addElementAttr(8, 'class', 'center');
  $alert_warning->addElementAttr(9, 'class', 'inline');
  $alert_warning->addElementAttr(10, 'class', 'set green');
  $alert_warning->addElementAttr(11, 'href, class, id', 'return: false;, btn pri ico, confirm');
  $alert_warning->addElementAttr(12, 'class', 'center');

  $alert_warning->generate();

  $alert_success = new Alert();
  $alert_success->addElement('div', 'true', null, '', 0, false);
  $alert_success->addElement('div', 'true', 1, '', 0, false);
  $alert_success->addElement('img', 'false', 2, $lang->get("MSG_RMV_SUCC"), 1, false);
  $alert_success->addElementAttr(1, 'class', 'alert success');
  $alert_success->addElementAttr(2, 'class', 'alert-success');
  $alert_success->addElementAttr(3, 'src', 'images/icons/succed32x32.png');

  $alert_success->generate();

  $alert_error = new Alert();
  $alert_error->addElement('div', 'true', null, '', 0, false);
  $alert_error->addElement('div', 'true', 1, '', 0, false);
  $alert_error->addElement('img', 'false', 2, $lang->get("MSG_RMV_ERR"), 1, false);
  $alert_error->addElementAttr(1, 'class', 'alert error');
  $alert_error->addElementAttr(2, 'class', 'alert-danger');
  $alert_error->addElementAttr(3, 'src', 'images/icons/error32x32.png');

  $alert_error->generate();

  $element = array(0 => new PanelElement($lang->get('DELETE_TABLE_PANEL0_HANDY'), $lang->get('DELETE_TABLE_PANEL0_TITLE'), $lang->get('DELETE_TABLE_PANEL0_DESCRIPTION'), $lang->get('DELETE_TABLE_PANEL0_TEXT').'<br><br>'.$alert_error->get().$alert_success->get().$alert_warning->get().prepareTables2Rmvl(2), '{ADDITIVE}', '2-sided', array('col' => 12)));

  $element[0]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('DELETE_TABLE_PANEL0_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=Managing - create, edit, delete -all', '', 0, 2, 'all_width', 16);

  elementsOutput();

  $js = new Template('delete-table', 'templates/js/');
  $js->_init();

  $manage = new Template('main', 'templates/');
  $manage->add('SITE.LANGUAGE', $config['language']);
  $manage->add('SITE.TITLE', $lang->get('DELETE_TABLE_SITE_TITLE'));
  $manage->add('SITE.DESCRIPTION', $lang->get('DELETE_TABLE_SITE_DESCRIPTION'));
  $manage->add('SITE.AUTHOR', $lang->get('DELETE_TABLE_SITE_AUTHOR'));
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
