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

$val = $_POST['val'];

echo date($val);

?>
