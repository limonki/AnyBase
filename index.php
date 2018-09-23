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

if($session->exist('user') && !isSetGet('session_logout')) $user = $session->get('user');
else
{
  if(isSetGet('session_logout'))
  {
    if(@!isset($_COOKIE['logged_out'])) setcookie('msg', conv2Send2JS($lang->get('SESSION_LOGOUT')), 0, '/');
    setcookie('logged_out', 'true', 0, '/');
  }
  $user = new User();
}

if(!$user->authorized())
{
  setcookie('username', $lang->get('USERNAME'), 0, '/');
  setcookie('password', $lang->get('PASSWORD'), 0, '/');
  setcookie('msg-login-error', conv2Send2JS($lang->get('LOGIN_ERROR_MSG')), 0, '/');
  setcookie('msg-accept-cookies', conv2Send2JS($lang->get('ACCEPT_COOKIES_MSG')), 0, '/');

  $session->insert('user', $user);

  $login_form = new Form();
  $login_form->addElement('form', 'true', null, '', 0, true);
  $login_form->addElement('input', 'false', 1, '', 0, true);
  $login_form->addElement('input', 'false', 1, '', 0, true);
  $login_form->addElement('input', 'false', 1, '', 0, false);
  $login_form->addElementAttr(1, 'id, method', 'login-panel, POST');
  $login_form->addElementAttr(2, 'type, class, name, value', 'text, username, username, '.$lang->get('USERNAME'));
  $login_form->addElementAttr(3, 'type, class, name, value', 'text, password, password, '.$lang->get('PASSWORD'));
  $login_form->addElementAttr(4, 'type, id, class, value', 'submit, button, input-submit, '.$lang->get('LOGIN'));
  $login_form->generate();

  $js = new Template('index', 'templates/js/');
  $js->_init();

  $index = new Template('index', 'templates/');
  $index->add('SITE.TITLE', $lang->get('INDEX_SITE_TITLE'));
  $index->add('SITE.DESCRIPTION', $lang->get('INDEX_SITE_DESCRIPTION'));
  $index->add('SITE.AUTHOR', $lang->get('INDEX_SITE_AUTHOR'));
  $index->add('IDENTIFY.MSG', $lang->get('INDEX_IDENTIFY_MSG'));
  $index->add('LOGIN.FORM', $login_form->get());
  $index->add('REGISTER.NEW.USER', $lang->get('INDEX_REGISTER_NEW_USER'));
  $index->add('FORGOTTEN', $lang->get('INDEX_FORGOTTEN'));
  $index->add('HELP', $lang->get('INDEX_HELP'));
  $index->add('SUPPORT', $lang->get('INDEX_SUPPORT_EMAIL'));
  $index->add('SUPPORT.EMAIL', $config['support_contact_email']);
  $index->add('COOKIES.TITLE', $lang->get('INDEX_COOKIES_TITLE'));
  $index->add('COOKIES.TEXT', $lang->get('INDEX_COOKIES_TEXT'));
  $index->add('COOKIES.AGREE', $lang->get('INDEX_COOKIES_AGREE'));
  $index->add('COOKIES.MORE.INFO', $lang->get('INDEX_COOKIES_MORE_INFO'));
  if(detectMobile()) $index->add('TERMS.AND.CONDITIONS', $lang->get('INDEX_TERMS_AND_CONDITIONS_MOBILE'));
  else $index->add('TERMS.AND.CONDITIONS', $lang->get('INDEX_TERMS_AND_CONDITIONS'));
  $index->add('PRIVACY.POLICY', $lang->get('INDEX_PRIVACY_POLICY'));
  $index->add('JS.SCRIPTS', $js->output());

  $index->_init();
  $index->show();
}
else header('Location: main.php');

if(!isset($_COOKIE['refreshed'])) setcookie('refreshed', 1, 0, '/');
else setcookie('refreshed', $_COOKIE['refreshed']+1, 0, '/');

?>
