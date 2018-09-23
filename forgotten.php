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
  $js = new Template('forgotten', 'templates/js/');
  $js->_init();

  $register = new Template('password', 'templates/');
  $register->add('SITE.LANGUAGE', $config['language']);
  $register->add('SITE.TITLE', $lang->get('FORGOTTEN_SITE_TITLE'));
  $register->add('SITE.DESCRIPTION', $lang->get('FORGOTTEN_SITE_DESCRIPTION'));
  $register->add('SITE.AUTHOR', $lang->get('FORGOTTEN_SITE_AUTHOR'));
  $register->add('RELOAD.PAGE', $lang->get('FORGOTTEN_RELOAD_PAGE'));
  $register->add('JS.SCRIPTS', $js->output());

  if(!isset($_GET['v']))
  {
    $session->insert('user', $user);

    setcookie('validation-errors', conv2Send2JS($lang->get('FORM_VALIDATION_ERRORS')), 0, '/');
    setcookie('email-is-empty', conv2Send2JS($lang->get('REGISTER_EMAIL_IS_EMPTY')), 0, '/');
    setcookie('email-not-exist', conv2Send2JS($lang->get('FORGOTTEN_EMAIL_NOT_EXIST')), 0, '/');

    $forgotten_form = new Form();
    $forgotten_form->addElement('div', 'true', null, '', 0, false);
    $forgotten_form->addElement('img', 'false', 1, '', 0, false);
    $forgotten_form->addElement('h2', 'true', null, $lang->get('FORGOTTEN_FORGOTTEN'), 1, false);
    $forgotten_form->addElement('div', 'true', null, '', 0, false);
    $forgotten_form->addElement('div', 'true', 4, '', 0, false);
    $forgotten_form->addElement('label', 'true', 5, '<b>'.$lang->get('REGISTER_EMAIL').'</b> <red>*</red>', 1, false);
    $forgotten_form->addElement('input', 'false', 5, '', 0, false);
    $forgotten_form->addElement('h', 'true', null, '<red>*</red> <b>'.$lang->get('REGISTER_REMEMBER').'</b>', 0, false);
    $forgotten_form->addElement('div', 'true', null, '', 0, false);
    $forgotten_form->addElement('span', 'true', 9, '', 0, false);
    $forgotten_form->addElement('a', 'true', 10, '', 0, false);
    $forgotten_form->addElement('div', 'true', 11, $lang->get('BACK'), 1, false);
    $forgotten_form->addElement('span', 'true', 9, '', 0, false);
    $forgotten_form->addElement('a', 'true', 13, '', 0, false);
    $forgotten_form->addElement('div', 'true', 14, $lang->get('FORGOTTEN_RESET'), 1, false);
    $forgotten_form->addElementAttr(1, 'class', 'center');
    $forgotten_form->addElementAttr(2, 'src', 'images/icon.png');
    $forgotten_form->addElementAttr(4, 'class', 'row');
    $forgotten_form->addElementAttr(7, 'type, class, name', 'email, title col-18, email');
    $forgotten_form->addElementAttr(9, 'class', 'submit');
    $forgotten_form->addElementAttr(10, 'class', 'set green');
    $forgotten_form->addElementAttr(11, 'type, class, id', 'submit, btn pri, button-back');
    $forgotten_form->addElementAttr(12, 'class', 'center');
    $forgotten_form->addElementAttr(13, 'class', 'f-right set red right');
    $forgotten_form->addElementAttr(14, 'type, class, id', 'submit, btn pri, button-reset');
    $forgotten_form->addElementAttr(15, 'class', 'center');
    $forgotten_form->generate();

    $register->add('FORM', $forgotten_form->get());
  }
  else
  {
    $link = $_GET['v'];

    $forgotten_form = new Form();
    $forgotten_form->addElement('div', 'true', null, '', 0, false);
    $forgotten_form->addElement('span', 'true', 1, '', 0, false);
    $forgotten_form->addElement('a', 'true', 2, '', 0, false);
    $forgotten_form->addElement('div', 'true', 3, $lang->get('BACK'), 1, false);
    $forgotten_form->addElementAttr(1, 'class', 'submit');
    $forgotten_form->addElementAttr(2, 'class', 'set green');
    $forgotten_form->addElementAttr(3, 'type, class, id', 'submit, btn pri, button-back');
    $forgotten_form->addElementAttr(4, 'class', 'center');
    $forgotten_form->generate();

    $db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
    $db->query("SELECT * FROM anybase_reset_pass WHERE link='".$link."'");

    if($db->numRows() > 0)
    {
      $data = $db->fetchAssoc();
      $db->query("DELETE FROM anybase_reset_pass WHERE link='".$link."'");

      $password = generateRandomString();

      $db->query("UPDATE anybase_user SET password='".url2hash($password)."' WHERE username='".$data['username']."'");

      $register->add('FORM', '<div class="center"><img src="images/icon.png"></div><h2>'.$lang->get('FORGOTTEN_FORGOTTEN')."</h2>".str_replace("{PASSWORD}", "<b>".$password."</b>", $lang->get("FORGOTTEN_PASS_RESETED")).$forgotten_form->get());
    }
    else $register->add('FORM', '<div class="center"><img src="images/icon.png"></div><h2>'.$lang->get('FORGOTTEN_FORGOTTEN')."</h2>".$lang->get("FORGOTTEN_PASS_RESETED_ERR").$forgotten_form->get());

    $db->close();
  }

  $register->_init();
  $register->show();
}
else header('Location: main.php');

?>
