<?php

require_once('includes/definitions.php');
require_once(__ROOT__.'/menu.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/template.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
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

  if(getVersion()[0] > $config['version']) header("Location: update.php");
  else if(isSetGet('v')) checkRedirect($_GET['v']);

  $menu->addElementAttr(1, 'class', 'active');
  $menu->addElementAttr(11, 'id', 'fold');

  $user_info = $user->info();

  $element = array(0 => new PanelElement($lang->get('MAIN_PANEL0_HANDY'), $lang->get('MAIN_PANEL0_TITLE'), $lang->get('MAIN_PANEL0_DESCRIPTION'), $lang->get('MAIN_PANEL0_TEXT'), $lang->get('MAIN_PANEL0_FOOT')."\n\n".'{ADDITIVE}', 'default', array('col' => 18)),
                   1 => new PanelElement($lang->get('MAIN_PANEL1_HANDY'), '', '', $lang->get('MAIN_PANEL1_TEXT'), '{ADDITIVE}', '2-sided', array('col' => 6)),
                   2 => new PanelElement($lang->get('MAIN_PANEL2_HANDY'), '', '', $lang->get('MAIN_PANEL2_TEXT'), '{ADDITIVE}', '2-sided', array('col' => 6)),
                   3 => new PanelElement($lang->get('MAIN_PANEL3_HANDY'), '', '', $lang->get('MAIN_PANEL3_TEXT'), '{ADDITIVE}', '2-sided', array('col' => 6, 'reset' => TRUE)),
                   4 => new PanelElement($lang->get('MAIN_PANEL4_HANDY'), $lang->get('MAIN_PANEL4_TITLE'), '', '{ADDITIVE}', $lang->get('MAIN_PANEL4_TEXT').' {ADDITIVE}', '2-sided', array('col' => 6, 'title_color' => 'red')),
                   5 => new PanelElement($lang->get('MAIN_PANEL5_HANDY'), $lang->get('MAIN_PANEL5_TITLE'), '', '{LAST.ACTIVITY}', '{ADDITIVE}', 'separated', array('col' => 8, 'title_color' => 'green')));

  $element[0]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('MAIN_PANEL0_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php', '', 0, 2, 'all_width', 16);

  $element[1]->createAdditive('link', 'foot', 'images/icons/create-grey32x32.png', $lang->get('MAIN_PANEL1_FOOT_ADDITIVE1'), 'manage.php?r='.url2hash('create-table.php'), '', 1, 2, 'img_div');
  $element[1]->createAdditive('link', 'foot', 'images/icons/delete-grey32x32.png', $lang->get('MAIN_PANEL1_FOOT_ADDITIVE2'), 'manage.php?r='.url2hash('delete-table.php'), '', 1, 2, 'img_div');
  $element[1]->createAdditive('link', 'foot', 'images/icons/relations-grey32x32.png', $lang->get('MAIN_PANEL1_FOOT_ADDITIVE3'), 'manage.php?r='.url2hash('relations.php'), '', 1, 2, 'img_div');

  $element[2]->createAdditive('link', 'foot', 'images/icons/see-grey32x32.png', $lang->get('MAIN_PANEL2_FOOT_ADDITIVE1'), 'tables.php', '', 1, 2, 'img_div');
  $element[2]->createAdditive('link', 'foot', 'images/icons/configure-grey32x32.png', $lang->get('MAIN_PANEL2_FOOT_ADDITIVE2'), 'configuration.php', '', 1, 2, 'img_div');
  $element[2]->createAdditive('link', 'foot', 'images/icons/backup-grey32x32.png', $lang->get('MAIN_PANEL2_FOOT_ADDITIVE3'), 'backup.php', '', 1, 2, 'img_div');

  $element[3]->createAdditive('link', 'foot', 'images/icons/users-grey32x32.png', $lang->get('MAIN_PANEL3_FOOT_ADDITIVE1'), 'users.php', '', 1, 2, 'img_div');

  $element[4]->createAdditive('image', 'body', 'images/backup.png', '', '', '', 1, 0, 'img_div');
  $element[4]->createAdditive('button', 'foot', 'images/icons/backup32x32.png', $lang->get('MAIN_PANEL4_FOOT_ADDITIVE1'), 'backup.php?backup-now&execute', '', 1, 0, 'img_div');

  $element[5]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('MAIN_PANEL5_FOOT_ADDITIVE1'), 'activity.php', '', 0, 2, 'all_width', 16);

  $db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
  $db->select("anybase_backup", '*', array('id' => 1));
  $db->close();

  $datetime1 = new DateTime($db->fetchAssoc()['last']);
  $datetime2 = new DateTime(date("Y-m-d H:i:s"));
  $interval = $datetime1->diff($datetime2);
  $days = $interval->format('%R%a days');

  if($days > 7 && $days <= 30)
  {
    $message[0] = "<span style=\"color: #e5b859; font-weight: bold;\">".$days."</span>";
    $message[1] = $lang->get("MAIN_FUNC1");
  }
  else if($days > 30)
  {
    $message[0] = "<span style=\"color: #e22f27; font-weight: bold;\">".$days."</span>";
    $message[1] = $lang->get("MAIN_FUNC2");
  }
  else
  {
    $message[0] = "<span style=\"color: #86c349; font-weight: bold;\">".$days."</span>";
    $message[1] = $lang->get("MAIN_FUNC3");
  }

  $element[4]->addKey('BACKUP.DAYS', str_replace('+', '', $message[0]));
  $element[4]->addKey('BACKUP.STATE', $message[1]);
  $element[4]->init();

  $element[5]->addKey('LAST.ACTIVITY', lastActivity($user_info['username'], 5));
  $element[5]->init();

  elementsOutput();

  $js = new Template('main', 'templates/js/');
  $js->_init();

  $main = new Template('main', 'templates/');
  $main->add('SITE.LANGUAGE', $config['language']);
  $main->add('SITE.TITLE', $lang->get('MAIN_SITE_TITLE'));
  $main->add('SITE.DESCRIPTION', $lang->get('MAIN_SITE_DESCRIPTION'));
  $main->add('SITE.AUTHOR', $lang->get('MAIN_SITE_AUTHOR'));
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
