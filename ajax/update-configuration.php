<?php

require_once('../includes/definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/database.php');
require_once(__ROOT__.'/includes/session.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/form.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$session = new Session();

$array = array();

if(isset($_POST['date'])) $array['date'] = $_POST['date'];
if(isset($_POST['time'])) $array['time'] = $_POST['time'];
if(isset($_POST['support_contact_email'])) $array['support_contact_email'] = $_POST['support_contact_email'];
if(isset($_POST['language'])) $array['language'] = $_POST['language'];
if(isset($_POST['session_expires'])) $array['session_expires'] = $_POST['session_expires'];
if(isset($_POST['backup_path'])) $array['backup_path'] = $_POST['backup_path'];
if(isset($_POST['auto_download'])) $array['auto_download'] = $_POST['auto_download'];

updateConfiguration($array);

?>
