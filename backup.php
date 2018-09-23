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

  $menu->addElementAttr(11, 'id', 'fold');

  $user_info = $user->info();

  $configure = new Form();
  $restore = new Form();

  $configure->addElement("div", "true", null);
  $configure->addElement("form", "true", 1);
  $configure->addElement("input", "false", 2, "", 0, false);
  $configure->addElement("label", "true", 2, "", 1, false);
  $configure->addElement("input", "false", 4, " auto-download", 1, true);
  $configure->addElement("span", "true", 2, "", 0, false);
  $configure->addElement("a", "true", 6, "<div class='center'>".$lang->get("SAVE")."</div>", 1, true);
  $configure->addElementAttr(1, "id", "configure");
  $configure->addElementAttr(2, "method, id", "POST, configure-form");
  $configure->addElementAttr(3, "type, class, name, value", "text, title col-18, backup-path, ".$config['backup_path']);
  if($config['auto_download']) $configure->addElementAttr(5, "type, class, name, checked", "checkbox, title, auto-download, true");
  else $configure->addElementAttr(5, "type, class, name", "checkbox, title, auto-download");
  $configure->addElementAttr(6, "class", "set green");
  $configure->addElementAttr(7, "type, class, id", "submit, btn pri ico, button-configure");

  $restore->addElement("div", "true", null);
  $restore->addElement("form", "true", 1);
  $restore->addElement("div", "true", 2, "", 2, false);
  $restore->addElement("div", "true", 3, "", 2, true);
  $restore->addElement("img", "false", 4, "", 1, false);
  $restore->addElement("span", "true", 4, $lang->get("CHOOSE_FILE")."...", 1, true);
  $restore->addElement("input", "false", 3, "", 0, true);
  $restore->addElement("span", "true", 2, "", 0, false);
  $restore->addElement("input", "true", 8, "", 1, true);
  $restore->addElementAttr(1, "id", "restore");
  $restore->addElementAttr(2, "method, id, enctype", "POST, restore-form, multipart/form-data");
  $restore->addElementAttr(3, "class", "center");
  $restore->addElementAttr(4, "class, id, onClick", "button-file, button-file-1, openFileSearch($(this),$(this).next().next());");
  $restore->addElementAttr(5, "src", "images\icons\upload20x25.png");
  $restore->addElementAttr(7, "type, name, id", "file, filename, restore-filename-1");
  $restore->addElementAttr(8, "class", "set red");
  $restore->addElementAttr(9, "type, class, id, value", "submit, btn pri, button-restore, ".$lang->get("RESTORE"));

  $restore->generate();

  $configure->generate();

  $element = array(0 => new PanelElement($lang->get("BACKUP_PANEL0_HANDY"), $lang->get("BACKUP_PANEL0_TITLE"), $lang->get("BACKUP_PANEL0_DESC"), "<div class=\"alert-info\"><img src=\"images/icons/info32x32.png?".time()."\"> ".$lang->get("BACKUP_PANEL0_INFO1")."</div>".$lang->get("BACKUP_PANEL0_TEXT")."<br><br>".$configure->get(), "{ADDITIVE}", "2-sided", array("col" => 8)),
                   1 => new PanelElement($lang->get("BACKUP_PANEL1_HANDY"), $lang->get("BACKUP_PANEL1_TITLE"), "", "<div class=\"alert-warning\"><img src=\"images/icons/alert32x32.png?".time()."\"> ".$lang->get("BACKUP_PANEL1_WARNING1")."</div>".$lang->get("BACKUP_PANEL1_TEXT")."<br><br>".$restore->get(), "{ADDITIVE}", "2-sided", array("col" => 10)),
                   2 => new PanelElement($lang->get("BACKUP_PANEL2_HANDY"), $lang->get("BACKUP_PANEL2_TITLE"), "", "<div id=\"backup-now\">{ADDITIVE}</div>", "{ADDITIVE}", "2-sided", array("col" => 10)));

  $element[0]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('BACKUP_PANEL0_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=Backup - configuration -all', '', 0, 2, 'all_width', 16);
  $element[1]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('BACKUP_PANEL1_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=Which data will be restored? -all', '', 0, 2, 'all_width', 16);
  $element[2]->createAdditive("button", "body", "images/icons/backup32x32.png", $lang->get("BACKUP_BUTTON1"), "return: false;", "", 1, 0, "img_div");

  elementsOutput();

  $js = new Template('backup', 'templates/js/');
  $js->_init();

  $manage = new Template('main', 'templates/');
  $manage->add('SITE.LANGUAGE', $config['language']);
  $manage->add('SITE.TITLE', $lang->get('BACKUP_SITE_TITLE'));
  $manage->add('SITE.DESCRIPTION', $lang->get('BACKUP_SITE_DESCRIPTION'));
  $manage->add('SITE.AUTHOR', $lang->get('BACKUP_SITE_AUTHOR'));
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
