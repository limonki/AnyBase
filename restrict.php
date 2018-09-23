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
require_once(__ROOT__.'/languages/installed.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();
$user = new User();

if($session->exist('user')) $user = $session->get('user');

if($user->authorized())
{
  $session->insert('user', $user);

  $menu->addElementAttr(9, 'class', 'active');
  $menu->addElementAttr(11, 'id', 'fold');

  $user_info = $user->info();

  $data = getTablesInfo();

  $restrict = new Form();

  $restrict->addElement("div", "true", null);
  $restrict->addElement("form", "true", 1);
  $restrict->addElement("select", "true", 2, "", 0, true, true);
  $offset_1 = $restrict->createOption($data, 3);
  $restrict->addElement("span", "true", 2, "", 0, false, true);
  $restrict->addElement("a", "true", $offset_1+4, "<div class='center'>".$lang->get("RESTRICT")."</div>", 1, true);
  $restrict->addElementAttr(1, "id", "advanced-settings");
  $restrict->addElementAttr(2, "method, id", "POST, advanced-settings-form");
  $restrict->addElementAttr(3, "type, class, name", "select, title select, restrict");
  $restrict->addElementAttr($offset_1+4, "class", "set red");
  $restrict->addElementAttr($offset_1+5, "type, class, id", "submit, btn pri, button-restrict");

  $restrict->generate();

  $data = getRestrictedAccess();

  $free = new Form();

  $free->addElement("div", "true", null);
  $free->addElement("form", "true", 1);
  $free->addElement("select", "true", 2, "", 0, true, true);
  $offset_1 = $free->createOption($data, 3);
  $free->addElement("span", "true", 2, "", 0, false, true);
  $free->addElement("a", "true", $offset_1+4, "<div class='center'>".$lang->get("FREE")."</div>", 1, true);
  $free->addElementAttr(1, "id", "advanced-settings");
  $free->addElementAttr(2, "method, id", "POST, advanced-settings-form");
  $free->addElementAttr(3, "type, class, name", "select, title select, free");
  $free->addElementAttr($offset_1+4, "class", "set green");
  $free->addElementAttr($offset_1+5, "type, class, id", "submit, btn pri, button-free");

  $free->generate();

  $element = array(0 => new PanelElement($lang->get('RESTRICT_PANEL0_HANDY'), $lang->get('RESTRICT_PANEL0_TITLE'), $lang->get('RESTRICT_PANEL0_DESC'), $lang->get('RESTRICT_PANEL0_TEXT').'<br><br>'.$restrict->get(), '{ADDITIVE}', '2-sided', array('col' => 9)),
                   1 => new PanelElement($lang->get('RESTRICT_PANEL1_HANDY'), $lang->get('RESTRICT_PANEL1_TITLE'), $lang->get('RESTRICT_PANEL1_DESC'), $lang->get('RESTRICT_PANEL1_TEXT').'<br><br>'.$free->get(), '{ADDITIVE}', '2-sided', array('col' => 9)));

  $element[0]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('RESTRICT_PANEL0_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=About restricting access -all', '', 0, 2, 'all_width', 16);
  $element[1]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('RESTRICT_PANEL1_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=About freeing access -all', '', 0, 2, 'all_width', 16);

  elementsOutput();

  $js = new Template('restrict', 'templates/js/');
  $js->_init();

  $manage = new Template('main', 'templates/');
  $manage->add('SITE.LANGUAGE', $config['language']);
  $manage->add('SITE.TITLE', $lang->get('RESTRICT_SITE_TITLE'));
  $manage->add('SITE.DESCRIPTION', $lang->get('RESTRICT_SITE_DESCRIPTION'));
  $manage->add('SITE.AUTHOR', $lang->get('RESTRICT_SITE_AUTHOR'));
  $manage->add('SITE.MENU', $menu->generate());
  $manage->add('USER.INFO', $user_info['firstname'].' '.$user_info['lastname']);
  $manage->add('USER.PERMISSION', convToPermissionStatus($user_info['permission']));
  $manage->add('USER.IMAGE.LOW', $user_info['avatar_low'].'?'.time());
  $manage->add('USER.IMAGE.HIGH', $user_info['avatar_high'].'?'.time());
  $manage->add('PANEL.ELEMENTS', $elements_output);
  $manage->add('PANEL.FOOT.TEXT', $lang->get('PANEL_FOOT_TEXT'));
  $manage->add('LOGOUT.LINK', 'index.php?session_logout');
  $manage->add('JS.SCRIPTS', $js->output());

  $manage->_init();
  $manage->show();
}
else fireExceptionMsg($lang->get('SESSION_UNAUTHORIZED'), 'session_unauthorized', 'index.php');

?>
