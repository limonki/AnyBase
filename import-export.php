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

  $menu->addElementAttr(10, 'class', 'active');
  $menu->addElementAttr(11, 'id', 'fold');

  $user_info = $user->info();

  $import_export = new Form();
  $import = new Form();

  $import->addElement("div", "true", null);
  $import->addElement("form", "true", 1);
  $import->addElement("div", "true", 2, "", 2, false);
  $import->addElement("div", "true", 3, "", 2, true);
  $import->addElement("img", "false", 4, "", 1, false);
  $import->addElement("span", "true", 4, $lang->get("CHOOSE_FILE")."...", 1, true);
  $import->addElement("input", "false", 3, "", 0, true);
  $import->addElement("span", "true", 2, "", 0, false);
  $import->addElement("input", "true", 8, "", 1, true);
  $import->addElementAttr(1, "id", "import");
  $import->addElementAttr(2, "method, id, enctype", "POST, import-form, multipart/form-data");
  $import->addElementAttr(3, "class", "center");
  $import->addElementAttr(4, "class, id, onClick", "button-file, button-file-1, openFileSearch($(this),$(this).next().next());");
  $import->addElementAttr(5, "src", "images\icons\upload20x25.png");
  $import->addElementAttr(7, "type, name, id", "file, filename, import-filename-1");
  $import->addElementAttr(8, "class", "set red");
  $import->addElementAttr(9, "type, class, id, value", "submit, btn pri, button-import, ".$lang->get("IMPORT"));

  $import->generate();

  $import_export->generate();

  $element = array(0 => new PanelElement($lang->get("IMPORT_EXPORT_PANEL0_HANDY"), $lang->get("IMPORT_EXPORT_PANEL0_TITLE"), "", $lang->get("IMPORT_EXPORT_PANEL0_TEXT")."<br><br>".$import->get(), "{ADDITIVE}", "2-sided", array("col" => 9)),
                   1 => new PanelElement($lang->get("IMPORT_EXPORT_PANEL1_HANDY"), $lang->get("IMPORT_EXPORT_PANEL1_TITLE"), "", $lang->get("IMPORT_EXPORT_PANEL1_TEXT")."<div id=\"backup-now\">{ADDITIVE}</div>", "{ADDITIVE}", "2-sided", array("col" => 9)));

  $element[1]->createAdditive("button", "body", "images/icons/backup32x32.png", $lang->get("IMPORT_EXPORT_BUTTON1"), "return: false;", "", 1, 0, "img_div");

  elementsOutput();

  $js = new Template('import-export', 'templates/js/');
  $js->_init();

  $manage = new Template('main', 'templates/');
  $manage->add('SITE.LANGUAGE', $config['language']);
  $manage->add('SITE.TITLE', $lang->get('IMPORT_EXPORT_SITE_TITLE'));
  $manage->add('SITE.DESCRIPTION', $lang->get('IMPORT_EXPORT_SITE_DESCRIPTION'));
  $manage->add('SITE.AUTHOR', $lang->get('IMPORT_EXPORT_SITE_AUTHOR'));
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
