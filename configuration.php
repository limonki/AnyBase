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

  $menu->addElementAttr(8, 'class', 'active');
  $menu->addElementAttr(11, 'id', 'fold');

  $user_info = $user->info();

  $session_expiration = new Form();
  $advanced_settings = new Form();
  $time_date_format = new Form();

  $session_expiration->addElement('form', 'true', null);
  $session_expiration->addElement('input', 'false', 1, '', 0, true, false);
  $session_expiration->addElement('span', 'true', 1, '', 0, false);
  $session_expiration->addElement('a', 'true', 3, '<div class="center">'.$lang->get('SAVE').'</div>', 1, true);
  $session_expiration->addElementAttr(1, 'method, id', 'POST, session-expiratio-form');
  $session_expiration->addElementAttr(2, 'type, class, name, value', 'number, title, session-expires, '.$config['session_expires'].'');
  $session_expiration->addElementAttr(3, 'class', 'set green');
  $session_expiration->addElementAttr(4, 'type, class, id', 'submit, btn pri ico, button-session-expiration');

  $session_expiration->generate();

  $advanced_settings->addElement("div", "true", null);
  $advanced_settings->addElement("form", "true", 1);
  $advanced_settings->addElement("input", "false", 2, "", 0, true, true);
  $advanced_settings->addElement("select", "true", 2, "", 0, true, true);
  $offset = $advanced_settings->createOption($installed_lang, 4);
  $advanced_settings->addElement("span", "true", 2, "", 0, false, true);
  $advanced_settings->addElement("a", "true", $offset+5, "<div class='center'>".$lang->get("SAVE")."</div>", 1, true);
  $advanced_settings->addElementAttr(1, "id", "advanced-settings");
  $advanced_settings->addElementAttr(2, "method, id", "POST, advanced-settings-form");
  $advanced_settings->addElementAttr(3, "type, class, name, value", "email, title col-18, support-contact-email, ".$config["support_contact_email"]);
  $advanced_settings->addElementAttr(4, "type, class, name", "select, title select, advanced-settings");
  $advanced_settings->addElementAttr($offset+5, "class", "set green");
  $advanced_settings->addElementAttr($offset+6, "type, class, id", "submit, btn pri ico, button-advanced-settings");

  $advanced_settings->generate();

  $time_date_format->addElement("div", "true");
  $time_date_format->addElement("form", "true", 1);
  $time_date_format->addElement("label", "true", 2, "", 0, false, false);
  $time_date_format->addElement("input", "false", 3, " ".date("j F Y"), 1, true, false);
  $time_date_format->addElement("label", "true", 2, "", 0, false, false);
  $time_date_format->addElement("input", "false", 5, " ".date("Y-m-d"), 1, true, false);
  $time_date_format->addElement("label", "true", 2, "", 0, false, false);
  $time_date_format->addElement("input", "false", 7, " ".date("m/d/Y"), 1, true, false);
  $time_date_format->addElement("label", "true", 2, "", 0, true, false);
  $time_date_format->addElement("input", "false", 9, " ".date("d/m/Y"), 1, true, false);
  $time_date_format->addElement("label", "true", 2, "", 0, true, false);
  $time_date_format->addElement("input", "false", 11, $lang->get("CONFIGURATION_FUNC1")."<input id=\"own-date\" type=\"text\" value=\"".$config["date"]."\" class=\"title-time-date\">", 1, true, false);
  $time_date_format->addElement("div", "true", 2, $lang->get("DATE_PREVIEW"), 1, true, false);
  $time_date_format->addElement("label", "true", 2, "", 0, false, false);
  $time_date_format->addElement("input", "false", 14, " ".date("H:i"), 1, true, false);
  $time_date_format->addElement("label", "true", 2, "", 0, true, false);
  $time_date_format->addElement("input", "false", 16, " ".date("g:i A"), 1, true, false);
  $time_date_format->addElement("label", "true", 2, "", 0, true, false);
  $time_date_format->addElement("input", "false", 18, $lang->get("CONFIGURATION_FUNC1")."<input id=\"own-time\" type=\"text\" value=\"".$config["time"]."\" class=\"title-time-date\">", 1, true, false);
  $time_date_format->addElement("div", "true", 2, $lang->get("TIME_PREVIEW"), 1, true, false);
  $time_date_format->addElement("span", "true", 2, "", 0, false);
  $time_date_format->addElement("a", "true", 21, "<div class='center'>".$lang->get("SAVE")."</div>", 1, true);
  $time_date_format->addElementAttr(1, "id", "time-date");
  $time_date_format->addElementAttr(2, "method, id", "POST, time-date-form");
  $time_date_format->addElementAttr(4, "type, name, value", "radio, date, j F Y");
  $time_date_format->addElementAttr(6, "type, name, value", "radio, date, Y-m-d");
  $time_date_format->addElementAttr(8, "type, name, value", "radio, date, m/d/Y");
  $time_date_format->addElementAttr(10, "type, name, value", "radio, date, d/m/Y");
  if(strcmp($config["date"], "j F Y") == 0) $time_date_format->addElementAttr(4, "checked", "");
  else if(strcmp($config["date"], "Y-m-d") == 0) $time_date_format->addElementAttr(6, "checked", "");
  else if(strcmp($config["date"], "m/d/Y") == 0) $time_date_format->addElementAttr(8, "checked", "");
  else if(strcmp($config["date"], "d/m/Y") == 0) $time_date_format->addElementAttr(10, "checked", "");
  else $time_date_format->addElementAttr(12, "checked", "");
  $time_date_format->addElementAttr(12, "type, id, name, value", "radio, check-own-date, date, ".$config["date"]."");
  $time_date_format->addElementAttr(13, "id", "preview-date");
  $time_date_format->addElementAttr(15, "type, name, value", "radio, time, H:i");
  $time_date_format->addElementAttr(17, "type, name, value", "radio, time, g:i A");
  if(strcmp($config["time"], "H:i") == 0) $time_date_format->addElementAttr(15, "checked", "");
  else if(strcmp($config["time"], "g:i A") == 0) $time_date_format->addElementAttr(17, "checked", "");
  else $time_date_format->addElementAttr(19, "checked", "");
  $time_date_format->addElementAttr(19, "type, id, name, value", "radio, check-own-time, time, ".$config["time"]."");
  $time_date_format->addElementAttr(20, "id", "preview-time");
  $time_date_format->addElementAttr(21, "class", "set green");
  $time_date_format->addElementAttr(22, "type, class, id", "submit, btn pri ico, button-time-date");

  $time_date_format->generate();

  $element = array(0 => new PanelElement($lang->get('CONFIGURATION_PANEL0_HANDY'), $lang->get('CONFIGURATION_PANEL0_TITLE'), $lang->get('CONFIGURATION_PANEL0_DESC'), '<div class="alert-warning"><img src="images/icons/alert32x32.png?'.time().'">'.$lang->get('CONFIGURATION_PANEL1_WARNING1').'</div> '.$lang->get('CONFIGURATION_PANEL0_TEXT').'<br><br>'.$session_expiration->get(), '{ADDITIVE}', '2-sided', array('col' => 7)),
                   1 => new PanelElement($lang->get('CONFIGURATION_PANEL1_HANDY'), $lang->get('CONFIGURATION_PANEL1_TITLE'), $lang->get('CONFIGURATION_PANEL1_DESC'), $lang->get('CONFIGURATION_PANEL1_TEXT').'<br><br>'.$advanced_settings->get(), '{ADDITIVE}', '2-sided', array('col' => 5)),
                   2 => new PanelElement($lang->get('CONFIGURATION_PANEL2_HANDY'), $lang->get('CONFIGURATION_PANEL2_TITLE'), $lang->get('CONFIGURATION_PANEL2_DESC'), '<div class="alert-info"><img src="images/icons/info32x32.png?'.time().'"> '.$lang->get('CONFIGURATION_PANEL3_INFO1').'</div>'.$lang->get('CONFIGURATION_PANEL2_TEXT').'<br><br>'.$time_date_format->get(), '{ADDITIVE}', '2-sided', array('col' => 6)));

  $element[0]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('CONFIGURATION_PANEL0_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=About session -all', '', 0, 2, 'all_width', 16);
  $element[1]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('CONFIGURATION_PANEL1_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=Advanced settings -all', '', 0, 2, 'all_width', 16);
  $element[2]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('CONFIGURATION_PANEL2_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=Time and data formats -all', '', 0, 2, 'all_width', 16);

  elementsOutput();

  $js = new Template('configuration', 'templates/js/');
  $js->_init();

  $manage = new Template('main', 'templates/');
  $manage->add('SITE.LANGUAGE', $config['language']);
  $manage->add('SITE.TITLE', $lang->get('CONFIGURATION_SITE_TITLE'));
  $manage->add('SITE.DESCRIPTION', $lang->get('CONFIGURATION_SITE_DESCRIPTION'));
  $manage->add('SITE.AUTHOR', $lang->get('CONFIGURATION_SITE_AUTHOR'));
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
