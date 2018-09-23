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

$update = array();

$user = htmlentities($_POST["user"], ENT_QUOTES);
if(isset($_POST["email"])) $update['email'] = htmlentities($_POST["email"], ENT_QUOTES);
if(isset($_POST["firstname"])) $update['firstname'] = htmlentities($_POST["firstname"], ENT_QUOTES);
if(isset($_POST["lastname"])) $update['lastname'] = htmlentities($_POST["lastname"], ENT_QUOTES);
if(isset($_POST["bio"])) $update['bio'] = htmlentities($_POST["bio"], ENT_QUOTES);
if(isset($_POST["permission"])) $update['permission'] = htmlentities($_POST["permission"], ENT_QUOTES);

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
$query = $db->update('anybase_user', $update, array('username' => $user));

if($query) echo 'Succedx01';
else echo 'Errorx01';

$db->close();

?>
