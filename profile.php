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
  setcookie('validation-errors', conv2Send2JS($lang->get('FORM_VALIDATION_ERRORS')), 0, '/');
  setcookie('password-too-short', conv2Send2JS($lang->get('REGISTER_PASSWORD_TOO_SHORT')), 0, '/');
  setcookie('password-not-same', conv2Send2JS($lang->get('REGISTER_PASS_NOT_SAME')), 0, '/');
  setcookie('username-is-empty', conv2Send2JS($lang->get('REGISTER_USERNAME_IS_EMPTY')), 0, '/');
  setcookie('username-too-short', conv2Send2JS($lang->get('REGISTER_USERNAME_TOO_SHORT')), 0, '/');
  setcookie('email-is-empty', conv2Send2JS($lang->get('REGISTER_EMAIL_IS_EMPTY')), 0, '/');
  setcookie('firstname-is-empty', conv2Send2JS($lang->get('REGISTER_FIRSTNAME_IS_EMPTY')), 0, '/');
  setcookie('lastname-is-empty', conv2Send2JS($lang->get('REGISTER_LASTNAME_IS_EMPTY')), 0, '/');

  $session->insert('user', $user);

  $menu->addElementAttr(11, 'id', 'fold');

  $user_info = $user->info();

  $basic = new Form();

  $basic->addElement('div', 'true', null, '', 0, true);
  $basic->addElement('form', 'true', 1, '', 0, true);
  $basic->addElement('input', 'false', 2, '', 0, true);
  $basic->addElement('input', 'false', 2, '', 0, true);
  $basic->addElement('input', 'false', 2, '', 0, true);
  $basic->addElement('input', 'false', 2, '', 0, true);
  $basic->addElement('textarea', 'true', 2, $user_info['bio'], 1, true);
  $basic->addElement('span', 'true', 2, '', 0, true);
  $basic->addElement('a', 'true', 8, '<div class="center">'.$lang->get('UPDATE').'</div>', 1, true);
  $basic->addElementAttr(1, 'id', 'basic');
  $basic->addElementAttr(2, 'method, id', 'POST, basic-form');
  $basic->addElementAttr(3, 'type, class, name, value', 'text, title col-18, username, '.$user_info['username']);
  $basic->addElementAttr(4, 'type, class, name, value', 'text, title col-18, firstname, '.$user_info['firstname']);
  $basic->addElementAttr(5, 'type, class, name, value', 'text, title col-18, lastname, '.$user_info['lastname']);
  $basic->addElementAttr(6, 'type, class, name, value', 'text, title col-18, email, '.$user_info['email']);
  $basic->addElementAttr(7, 'class, name', 'title col-18, bio');
  $basic->addElementAttr(8, 'class', 'set blue');
  $basic->addElementAttr(9, 'type, class, id', 'submit, btn pri ico, button-update');

  $basic->generate();

  //$avatar = new Form('file', false, 1, false, false, 'form-execute-send-file.php');
  $avatar = new Form();

  $avatar->addElement('div', 'true');
  $avatar->addElement('form', 'true', 1);
  $avatar->addElement('div', 'true', 2, '', 2, false);
  $avatar->addElement('div', 'true', 3, '', 2, true);
  $avatar->addElement('img', 'false', 4, '', 1, false);
  $avatar->addElement('span', 'true', 4, $lang->get('CHOOSE_FILE').'...', 1, true);
  $avatar->addElement('input', 'false', 3, '', 0, true);
  $avatar->addElement('span', 'true', 2, '', 0, false);
  $avatar->addElement('input', 'true', 8, '', 1, true);
  $avatar->addElement('input', 'false', 2, '', 0, false);
  $avatar->addElement('input', 'false', 2, '', 0, false);
  $avatar->addElement('input', 'false', 2, '', 0, false);
  $avatar->addElement('input', 'false', 2, '', 0, false);
  $avatar->addElementAttr(1, 'id', 'avatar');
  $avatar->addElementAttr(2, 'method, id, enctype', 'POST, avatar-form, multipart/form-data');
  $avatar->addElementAttr(3, 'class', 'center');
  $avatar->addElementAttr(4, 'class, id, onClick', 'button-file, button-file-2, openFileSearch($(this),$(this).next().next());');
  $avatar->addElementAttr(5, 'src', 'images\icons\upload20x25.png');
  $avatar->addElementAttr(7, 'type, name, id', 'file, filename, avatar-filename-2');
  $avatar->addElementAttr(8, 'class', 'set green');
  $avatar->addElementAttr(9, 'type, class, id', 'submit, btn pri ico, button-send, '.$lang->get('UPDATE'));
  $avatar->addElementAttr(10, 'type, name, value', 'hidden, avatar_high, '.$user_info['avatar_high']);
  $avatar->addElementAttr(11, 'type, name, value', 'hidden, avatar_low, '.$user_info['avatar_low']);
  $avatar->addElementAttr(12, 'type, name, value', 'hidden, max_size, 64x64');
  $avatar->addElementAttr(13, 'type, name, value', 'hidden, create_miniature, 32x32');

  $avatar->generate();

  $password = new Form();
  $password->addElement('div', 'true');
  $password->addElement('form', 'true', 1);
  $password->addElement('input', 'false', 2, '', 0, true);
  $password->addElement('input', 'false', 2, '', 0, true);
  $password->addElement('input', 'false', 2, '', 0, true);
  $password->addElement('span', 'true', 2, '', 0, false);
  $password->addElement('a', 'true', 6, '<div class="center">'.$lang->get('CHANGE').'</div>', 1, true);
  $password->addElement('input', 'false', 2, '', 0, false);
  $password->addElementAttr(1, 'id', 'password');
  $password->addElementAttr(2, 'method, id', 'POST, password-form');
  $password->addElementAttr(3, 'type, class, name, value', 'text, title col-18 password, old, '.$lang->get('OLD_PASS'));
  $password->addElementAttr(4, 'type, class, name, value', 'text, title col-18 password, new, '.$lang->get('NEW_PASS'));
  $password->addElementAttr(5, 'type, class, name, value', 'text, title col-18 password, confirm, '.$lang->get('CNFRM_PASS'));
  $password->addElementAttr(6, 'class', 'set blue');
  $password->addElementAttr(7, 'type, class, id', 'submit, btn pri ico, button-password');
  $password->addElementAttr(8, 'type, name, value', 'hidden, username, '.$user_info['username']);

  $password->generate();

  $element = array(0 => new PanelElement($lang->get('PROFILE_PANEL0_HANDY'), $lang->get('PROFILE_PANEL0_TITLE'), $lang->get('PROFILE_PANEL0_DESC'), $lang->get('PROFILE_PANEL0_TEXT').'<br><br>{BASIC}', '{ADDITIVE}', '2-sided', array('col' => 6)),
                   1 => new PanelElement($lang->get('PROFILE_PANEL1_HANDY'), $lang->get('PROFILE_PANEL1_TITLE'), $lang->get('PROFILE_PANEL1_DESC'), '<div class="alert-info"><img src="images/icons/info32x32.png"> '.$lang->get('PROFILE_PANEL1_INFO1').'</div>{AVATAR}{CHANGE.AVATAR}', '{ADDITIVE}', '2-sided', array('col' => 6)),
                   2 => new PanelElement($lang->get('PROFILE_PANEL2_HANDY'), $lang->get('PROFILE_PANEL2_TITLE'), $lang->get('PROFILE_PANEL2_DESC'), $lang->get('PROFILE_PANEL2_TEXT').'<br><br>{PASSWORD}', '{ADDITIVE}', '2-sided', array('col' => 6)));

  $element[0]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('PROFILE_PANEL0_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=Profile - basic informations -all', '', 0, 2, 'all_width', 16);
  $element[0]->addKey('BASIC', $basic->get());
  $element[0]->init();

  $element[1]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('PROFILE_PANEL1_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=Profile - avatar: size, image type -all', '', 0, 2, 'all_width', 16);
  $element[1]->addKey('AVATAR', '<div class="center"><img src="'.$user_info['avatar_high'].'?'.time().'"></div><br>');
  $element[1]->addKey('CHANGE.AVATAR', $avatar->get());
  $element[1]->init();

  $element[2]->createAdditive('link', 'foot', 'images/icons/get-help32x32.png', $lang->get('PROFILE_PANEL2_FOOT_ADDITIVE1'), 'http://www.anybase.cba.pl/help.php?search=Profile - password requirements -all', '', 0, 2, 'all_width', 16);
  $element[2]->addKey('PASSWORD', $password->get());
  $element[2]->init();

  elementsOutput();

  $js = new Template('profile', 'templates/js/');
  $js->_init();

  $main = new Template('main', 'templates/');
  $main->add('SITE.LANGUAGE', $config['language']);
  $main->add('SITE.TITLE', $lang->get('PROFILE_SITE_TITLE'));
  $main->add('SITE.DESCRIPTION', $lang->get('PROFILE_SITE_DESCRIPTION'));
  $main->add('SITE.AUTHOR', $lang->get('PROFILE_SITE_AUTHOR'));
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
