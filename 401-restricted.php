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
require_once(__ROOT__.'/includes/activity.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();
$user = new User();

if($session->exist('user')) $user = $session->get('user');

if($user->authorized())
{
  $session->insert('user', $user);

  $_401_restricted = new Template('codes', 'templates/');

  $_401_restricted->add('SITE.LANGUAGE', $config['language']);
  $_401_restricted->add('SITE.TITLE', $lang->get('RESTRICTED_SITE_TITLE'));
  $_401_restricted->add('SITE.DESCRIPTION', $lang->get('RESTRICTED_SITE_DESCRIPTION'));
  $_401_restricted->add('SITE.AUTHOR', $lang->get('RESTRICTED_SITE_AUTHOR'));
  $_401_restricted->add('IMG', 'images/icons/401-restricted48x48.png');
  $_401_restricted->add('RESTRICTED.TITLE', $lang->get('RESTRICTED_TITLE'));
  $_401_restricted->add('RESTRICTED.MSG', $lang->get('RESTRICTED_MSG'));
  $_401_restricted->add('RESTRICTED.BTN', $lang->get('RESTRICTED_BTN'));

  $_401_restricted->_init();
  $_401_restricted->show();
}
else fireExceptionMsg($lang->get('SESSION_UNAUTHORIZED'), 'session_unauthorized', 'index.php');

?>
