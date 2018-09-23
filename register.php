<?php

require_once('includes/definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/template.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/form.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();

if($session->exist('user')) $user = $session->get('user');
else $user = new User();

if(!$user->authorized())
{
  $session->insert('user', $user);

  setcookie('validation-errors', conv2Send2JS($lang->get('FORM_VALIDATION_ERRORS')), 0, '/');
  setcookie('username-too-short', conv2Send2JS($lang->get('REGISTER_USERNAME_TOO_SHORT')), 0, '/');
  setcookie('password-too-short', conv2Send2JS($lang->get('REGISTER_PASSWORD_TOO_SHORT')), 0, '/');
  setcookie('firstname-is-empty', conv2Send2JS($lang->get('REGISTER_FIRSTNAME_IS_EMPTY')), 0, '/');
  setcookie('lastname-is-empty', conv2Send2JS($lang->get('REGISTER_LASTNAME_IS_EMPTY')), 0, '/');
  setcookie('password-is-empty', conv2Send2JS($lang->get('REGISTER_PASSWORD_IS_EMPTY')), 0, '/');
  setcookie('username-is-empty', conv2Send2JS($lang->get('REGISTER_USERNAME_IS_EMPTY')), 0, '/');
  setcookie('email-is-empty', conv2Send2JS($lang->get('REGISTER_EMAIL_IS_EMPTY')), 0, '/');
  setcookie('user-taken', conv2Send2JS($lang->get('REGISTER_USERNAME_TAKEN')), 0, '/');
  setcookie('email-taken', conv2Send2JS($lang->get('REGISTER_EMAIL_TAKEN')), 0, '/');

  $registration_form = new Form();
  $registration_form->addElement('div', 'true', null, '', 0, false);
  $registration_form->addElement('img', 'false', 1, '', 0, false);
  $registration_form->addElement('h2', 'true', null, $lang->get('REGISTER_REGISTER'), 1, false);
  $registration_form->addElement('div', 'true', null, '', 0, false);
  $registration_form->addElement('div', 'true', 4, '', 0, false);
  $registration_form->addElement('h5', 'true', 5, $lang->get('REGISTER_NAME'), 1, false);
  $registration_form->addElement('div', 'true', 5, '', 0, false);
  $registration_form->addElement('label', 'true', 7, '<b>'.$lang->get('REGISTER_FIRST').'</b> <red>*</red>', 1, true);
  $registration_form->addElement('input', 'false', 7, '', 0, false);
  $registration_form->addElement('div', 'true', 5, '', 0, false);
  $registration_form->addElement('label', 'true', 10, '<b>'.$lang->get('REGISTER_LAST').'</b> <red>*</red>', 1, true);
  $registration_form->addElement('input', 'false', 10, '', 0, false);
  $registration_form->addElement('div', 'true', 4, '', 0, false);
  $registration_form->addElement('label', 'true', 13, '<b>'.$lang->get('REGISTER_USERNAME').'</b> <red>*</red>', 1, false);
  $registration_form->addElement('input', 'false', 13, '', 0, false);
  $registration_form->addElement('div', 'true', 4, '', 0, false);
  $registration_form->addElement('label', 'true', 16, '<b>'.$lang->get('REGISTER_PASSWORD').'</b> <red>*</red>', 1, false);
  $registration_form->addElement('input', 'false', 16, '', 0, false);
  $registration_form->addElement('div', 'true', 4, '', 0, false);
  $registration_form->addElement('label', 'true', 19, '<b>'.$lang->get('REGISTER_EMAIL').'</b> <red>*</red>', 1, false);
  $registration_form->addElement('input', 'false', 19, '', 0, false);
  $registration_form->addElement('div', 'true', 4, '', 0, false);
  $registration_form->addElement('label', 'true', 22, '<b>'.$lang->get('REGISTER_BIO').'</b>', 1, false);
  $registration_form->addElement('textarea', 'true', 22, '', 0, false);
  $registration_form->addElement('h', 'true', null, '<red>*</red> <b>'.$lang->get('REGISTER_REMEMBER').'</b>', 0, false);
  $registration_form->addElement('div', 'true', null, '', 0, false);
  $registration_form->addElement('span', 'true', 26, '', 0, false);
  $registration_form->addElement('a', 'true', 27, '', 0, false);
  $registration_form->addElement('div', 'true', 28, $lang->get('BACK'), 1, false);
  $registration_form->addElement('span', 'true', 26, '', 0, false);
  $registration_form->addElement('a', 'true', 30, '', 0, false);
  $registration_form->addElement('div', 'true', 31, $lang->get('REGISTER_REGISTER'), 1, false);
  $registration_form->addElementAttr(1, 'class', 'center');
  $registration_form->addElementAttr(2, 'src', 'images/icon.png');
  $registration_form->addElementAttr(4, 'class', 'row');
  $registration_form->addElementAttr(7, 'class', 'col-9');
  $registration_form->addElementAttr(9, 'type, class, name', 'text, f-left title col-17, firstname');
  $registration_form->addElementAttr(10, 'class', 't-right col-9');
  $registration_form->addElementAttr(12, 'type, class, name', 'text, f-right title col-17, lastname');
  $registration_form->addElementAttr(15, 'type, class, name', 'text, title col-18, username');
  $registration_form->addElementAttr(18, 'type, class, name', 'password, title col-18, password');
  $registration_form->addElementAttr(21, 'type, class, name', 'email, title col-18, email');
  $registration_form->addElementAttr(24, 'class, name', 'title col-18, bio');
  $registration_form->addElementAttr(26, 'class', 'submit');
  $registration_form->addElementAttr(27, 'class', 'set green');
  $registration_form->addElementAttr(28, 'type, class, id', 'submit, btn pri, button-back');
  $registration_form->addElementAttr(29, 'class', 'center');
  $registration_form->addElementAttr(30, 'class', 'f-right set red right');
  $registration_form->addElementAttr(31, 'type, class, id', 'submit, btn pri, button-register');
  $registration_form->addElementAttr(32, 'class', 'center');
  $registration_form->generate();

  $js = new Template('register', 'templates/js/');
  $js->_init();

  $register = new Template('password', 'templates/');
  $register->add('SITE.LANGUAGE', $config['language']);
  $register->add('SITE.TITLE', $lang->get('REGISTER_SITE_TITLE'));
  $register->add('SITE.DESCRIPTION', $lang->get('REGISTER_SITE_DESCRIPTION'));
  $register->add('SITE.AUTHOR', $lang->get('REGISTER_SITE_AUTHOR'));
  $register->add('RELOAD.PAGE', $lang->get('REGISTER_RELOAD_PAGE'));
  $register->add('FORM', $registration_form->get());
  $register->add('JS.SCRIPTS', $js->output());

  $register->_init();
  $register->show();
}
else header('Location: main.php');

?>
