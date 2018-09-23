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
require_once(__ROOT__.'/includes/activity.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();
$user = new User();

if($session->exist('user')) $user = $session->get('user');

if($user->authorized())
{
  $session->insert('user', $user);

  $menu->addElementAttr(11, 'id', 'fold');

  $user_info = $user->info();

  if(getVersion()[0] > $config['version'])
  {
    $output = $lang->get('UPDATE_PANEL0_TEXT1').'<div class="center wait"><img src="images/loader6.gif"></div>';
    downloadUpdate(getVersion()[1]);
  }
  else $output = $lang->get('UPDATE_PANEL0_TEXT2').'<div class="center wait"><img src="images/icons/succed110x110.png"></div>';
  $element = array(0 => new PanelElement($lang->get('UPDATE_PANEL0_HANDY'), $lang->get('UPDATE_PANEL0_TITLE'), '', $output, '{ADDITIVE}', '2-sided', array('col' => 9)));

  elementsOutput();

  $js = new Template('update', 'templates/js/');
  $js->_init();

  $manage = new Template('main', 'templates/');
  $manage->add('SITE.LANGUAGE', $config['language']);
  $manage->add('SITE.TITLE', $lang->get('UPDATE_SITE_TITLE'));
  $manage->add('SITE.DESCRIPTION', $lang->get('UPDATE_SITE_DESCRIPTION'));
  $manage->add('SITE.AUTHOR', $lang->get('UPDATE_SITE_AUTHOR'));
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
