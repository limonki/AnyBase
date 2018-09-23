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
require_once(__ROOT__.'/includes/activity.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();
$user = new User();

if($session->exist('user')) $user = $session->get('user');

if($user->authorized())
{
  $session->insert('user', $user);

  setcookie('user-not-updated', conv2Send2JS($lang->get('USER_NOT_UPDATED')), 0, '/');
  setcookie('user-success', conv2Send2JS($lang->get('USER_SUCCESS')), 0, '/');

  $user_info = $user->info();

  $alert_info = new Alert();
  $alert_info->addElement('div', 'true', null, '', 0, false);
  $alert_info->addElement('img', 'false', 1, $lang->get("USERS_PANEL0_INFO1"), 1, false);
  $alert_info->addElementAttr(1, 'class', 'alert-info');
  $alert_info->addElementAttr(2, 'src', 'images/icons/info32x32.png');

  $alert_info->generate();

  $generate_link = new Form();
  $users = new Form();

  $generate_link->addElement("div", "true", null);
  $generate_link->addElement("form", "true", 1);
  $generate_link->addElement("select", "true", 2, "", 0, true);
  $offset = $generate_link->createOption($permission_lvl, 3);
  $generate_link->addElement("span", "true", 2, "", 0, true);
  $generate_link->addElement("a", "true", $offset+4, "<div class='center'>".$lang->get("GENERATE")."</div>", 1, true);
  $generate_link->addElement("input", "false", 2, "", 0, true);
  $generate_link->addElement("span", "true", 2, "", 0, true);
  $generate_link->addElement("a", "true", $offset+7, "<div class='center'>".$lang->get("CLEAR_LINKS")."</div>", 1, true);
  $generate_link->addElementAttr(1, "id", "configure");
  $generate_link->addElementAttr(2, "method, id", "POST, configure-form");
  $generate_link->addElementAttr(3, "type, class, name", "select, select title, level");
  $generate_link->addElementAttr($offset+4, "class", "set green");
  $generate_link->addElementAttr($offset+5, "type, class, id", "submit, btn pri, button-generate");
  $generate_link->addElementAttr($offset+6, "type, class, id, value", "text, title col-18, reg-link, ".$lang->get('REG_LINK'));
  $generate_link->addElementAttr($offset+7, "class", "set red");
  $generate_link->addElementAttr($offset+8, "type, class, id", "submit, btn pri, button-clear");

  $generate_link->generate();

  $db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
  $db->query("SELECT * FROM anybase_user");

  $arr = array();
  array_push($arr, '---');
  while($row = $db->fetchAssoc())
  {
    if(!strcmp($row['username'], $user_info['username']) == 0) array_push($arr, $row['username']);
  }

  $db->close();

  $users->addElement("div", "true", null);
  $users->addElement("form", "true", 1);
  $users->addElement("select", "true", 2, "Select user to edit: ", 0, true);
  $offset = $users->createOption($arr, 3);
  $users->addElementAttr(1, "id", "configure");
  $users->addElementAttr(2, "method, id", "POST, edit-form");
  $users->addElementAttr(3, "type, class, name", "select, select title, user");

  $users->generate();

  $element = array(0 => new PanelElement($lang->get('USERS_PANEL0_HANDY'), $lang->get('USERS_PANEL0_TITLE'), $lang->get('USERS_PANEL0_DESC'), $alert_info->get().$lang->get("USERS_PANEL0_TEXT")."\n\n".$generate_link->get(), '{ADDITIVE}', '2-sided', array('col' => 6)),
                   1 => new PanelElement($lang->get('USERS_PANEL1_HANDY'), $lang->get('USERS_PANEL1_TITLE'), $lang->get('USERS_PANEL1_DESC'), $users->get(), '{ADDITIVE}', '2-sided', array('col' => 12)));

  $element[0]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('USERS_PANEL0_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=About generating links -all', '', 0, 2, 'all_width', 16);

  elementsOutput();

  $js = new Template('users', 'templates/js/');
  $js->_init();

  $manage = new Template('main', 'templates/');
  $manage->add('SITE.LANGUAGE', $config['language']);
  $manage->add('SITE.TITLE', $lang->get('USERS_SITE_TITLE'));
  $manage->add('SITE.DESCRIPTION', $lang->get('USERS_SITE_DESCRIPTION'));
  $manage->add('SITE.AUTHOR', $lang->get('USERS_SITE_AUTHOR'));
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
