<?php

require_once('includes/definitions.php');
require_once(__ROOT__.'/includes/template.php');
require_once(__ROOT__.'/includes/form.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$db = @new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
@$db->query("SELECT * FROM anybase_status");

$install = new Template('install', 'templates/');
$install->add('SITE.TITLE', $lang->get('INSTALL_SITE_TITLE'));
$install->add('SITE.DESCRIPTION', $lang->get('INSTALL_SITE_DESCRIPTION'));
$install->add('SITE.AUTHOR', $lang->get('INSTALL_SITE_AUTHOR'));

setcookie('installation-msg', conv2Send2JS($lang->get('INSTALL_INSTALLATION_MSG')), 0, '/');
setcookie('installation-succ', conv2Send2JS($lang->get('INSTALL_INSTALLATION_SUCC')), 0, '/');
setcookie('installation-err', conv2Send2JS($lang->get('INSTALL_INSTALLATION_ERR')), 0, '/');
setcookie('fill-empty-fields', conv2Send2JS($lang->get('INSTALL_FILL_EMPTY_FIELDS')), 0, '/');
setcookie('db-server-help', conv2Send2JS($lang->get('INSTALL_DB_SERVER_HELP')), 0, '/');
setcookie('db-username-help', conv2Send2JS($lang->get('INSTALL_DB_USERNAME_HELP')), 0, '/');
setcookie('db-password-help', conv2Send2JS($lang->get('INSTALL_DB_PASSWORD_HELP')), 0, '/');
setcookie('db-database-help', conv2Send2JS($lang->get('INSTALL_DB_DATABASE_HELP')), 0, '/');
setcookie('db-database-prfx-help', conv2Send2JS($lang->get('INSTALL_DB_DATABASE_PRFX_HELP')), 0, '/');
setcookie('session-expires-help', conv2Send2JS($lang->get('INSTALL_SESSION_EXPIRES_HELP')), 0, '/');
setcookie('support-contact-email-help', conv2Send2JS($lang->get('INSTALL_SUPPROT_CONTACT_EMAIL_HELP')), 0, '/');

if(!$data = @$db->fetchAssoc())
{
  $install_form = new Form();

  $install_form->addElement('div', 'true', null, '', 0, true);
  $install_form->addElement('form', 'true', 1, '', 0, true);
  $install_form->addElement('div', 'true', 2, '', 0, false);
  $install_form->addElement('div', 'true', 3, '', 0, false);
  $install_form->addElement('label', 'true', 4, '<b>'.$lang->get("DB_SERVER").'</b>', 1, true);
  $install_form->addElement('input', 'false', 4, '<div class="help db_server"></div>', 0, true);
  $install_form->addElement('div', 'true', 3, '', 0, false);
  $install_form->addElement('label', 'true', 7, '<b>'.$lang->get("DB_USERNAME").'</b>', 1, true);
  $install_form->addElement('input', 'false', 7, '<div class="help db_username"></div>', 0, true);
  $install_form->addElement('div', 'true', 3, '', 0, false);
  $install_form->addElement('label', 'true', 10, '<b>'.$lang->get("DB_PASSWORD").'</b>', 1, true);
  $install_form->addElement('input', 'false', 10, '<div class="help db_password"></div>', 0, true);
  $install_form->addElement('div', 'true', 3, '', 0, false);
  $install_form->addElement('label', 'true', 13, '<b>'.$lang->get("DB_DATABASE").'</b>', 1, true);
  $install_form->addElement('input', 'false', 13, '<div class="help db_database"></div>', 0, true);
  $install_form->addElement('div', 'true', 3, '', 0, false);
  $install_form->addElement('label', 'true', 16, '<b>'.$lang->get("DB_DATABASE_PRFX").'</b>', 1, true);
  $install_form->addElement('input', 'false', 16, '<div class="help db_database_prfx"></div>', 0, true);
  $install_form->addElement('div', 'true', 3, '', 0, false);
  $install_form->addElement('label', 'true', 19, '<b>'.$lang->get("SESSION_EXPIRES").'</b>', 1, true);
  $install_form->addElement('input', 'false', 19, '<div class="help session_expires"></div>', 0, true);
  $install_form->addElement('div', 'true', 3, '', 0, false);
  $install_form->addElement('label', 'true', 22, '<b>'.$lang->get("SUPPORT_CONTACT_EMAIL").'</b>', 1, true);
  $install_form->addElement('input', 'false', 22, '<div class="help support_contact_email"></div>', 0, true);
  $install_form->addElement('div', 'true', null, '', 0, false);
  $install_form->addElement('span', 'true', 25, '', 0, false);
  $install_form->addElement('a', 'true', 26, '', 0, false);
  $install_form->addElement('div', 'true', 27, $lang->get('PREVIOUS'), 1, false);
  $install_form->addElement('span', 'true', 25, '', 0, false);
  $install_form->addElement('a', 'true', 29, '', 0, false);
  $install_form->addElement('div', 'true', 30, $lang->get('NEXT'), 1, false);
  $install_form->addElement('span', 'true', 25, '', 0, false);
  $install_form->addElement('a', 'true', 32, '', 0, false);
  $install_form->addElement('div', 'true', 33, $lang->get('INSTALL'), 1, false);
  $install_form->addElement('div', 'true', null, '', 0, false);
  $install_form->addElement('span', 'true', 35, '', 0, false);
  $install_form->addElement('a', 'true', 36, '', 0, false);
  $install_form->addElement('div', 'true', 37, $lang->get('START'), 1, false);
  $install_form->addElementAttr(1, 'class', 'col-18 all');
  $install_form->addElementAttr(2, 'method, id', 'POST, install-form');
  $install_form->addElementAttr(3, 'class', 'row');
  $install_form->addElementAttr(4, 'class, style', 'col-18, display: none;');
  $install_form->addElementAttr(6, 'type, class, name, placeholder', 'text, title col-9, db_server, localhost');
  $install_form->addElementAttr(7, 'class, style', 'col-18, display: none;');
  $install_form->addElementAttr(9, 'type, class, name, placeholder', 'text, title col-9, db_username, admin');
  $install_form->addElementAttr(10, 'class, style', 'col-18, display: none;');
  $install_form->addElementAttr(12, 'type, class, name, placeholder', 'password, title col-9, db_password, admin');
  $install_form->addElementAttr(13, 'class, style', 'col-18, display: none;');
  $install_form->addElementAttr(15, 'type, class, name, placeholder', 'text, title col-9, db_database, anybase');
  $install_form->addElementAttr(16, 'class, style', 'col-18, display: none;');
  $install_form->addElementAttr(18, 'type, class, name, placeholder', 'text, title col-9, db_database_prfx, ab_');
  $install_form->addElementAttr(19, 'class, style', 'col-18, display: none;');
  $install_form->addElementAttr(21, 'type, class, name, placeholder', 'number, title col-9, session_expires, 3200');
  $install_form->addElementAttr(22, 'class, style', 'col-18, display: none;');
  $install_form->addElementAttr(24, 'type, class, name, placeholder', 'email, title col-9, support_contact_email, admin@example.com');
  $install_form->addElementAttr(25, 'class', 'submit');
  $install_form->addElementAttr(26, 'class', 'set red');
  $install_form->addElementAttr(27, 'type, class, id', 'submit, btn pri, button-back');
  $install_form->addElementAttr(28, 'class', 'center');
  $install_form->addElementAttr(29, 'class', 'f-right set blue right');
  $install_form->addElementAttr(30, 'type, class, id', 'submit, btn pri, button-next');
  $install_form->addElementAttr(31, 'class', 'center');
  $install_form->addElementAttr(32, 'class', 'f-right set green right');
  $install_form->addElementAttr(33, 'type, class, id', 'submit, btn pri, button-install');
  $install_form->addElementAttr(34, 'class', 'center');
  $install_form->addElementAttr(35, 'class', 'start center');
  $install_form->addElementAttr(36, 'class', 'set green');
  $install_form->addElementAttr(37, 'type, class, id', 'submit, btn pri, button-start');
  $install_form->addElementAttr(38, 'class', 'center');

  $install_form->generate();

  $install->add('MAIN', '<h2 class="center">'.$lang->get("INSTALL_WELCOME").'</h2>'.$install_form->get());
}
else
{
  $install_form = new Form();

  $install_form->addElement('div', 'true', null, '', 0, false);
  $install_form->addElement('span', 'true', 1, '', 0, false);
  $install_form->addElement('a', 'true', 2, '', 0, false);
  $install_form->addElement('div', 'true', 3, $lang->get('BACK'), 1, false);
  $install_form->addElementAttr(1, 'class', 'start center');
  $install_form->addElementAttr(2, 'class', 'set red');
  $install_form->addElementAttr(3, 'type, class, id', 'submit, btn pri, button-index');
  $install_form->addElementAttr(4, 'class', 'center');

  $install_form->generate();

  $install->add('MAIN', '<h2 class="center">'.$lang->get("INSTALL_DONE").'</h2>'.$install_form->get());
}

$install->_init();
$install->show();

?>
