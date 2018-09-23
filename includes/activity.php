<?php

require_once('definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/database.php');

class Activity
{
	public function add($icon, $key, $user)
	{
		global $config;

    $db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
    $db->query("INSERT INTO anybase_activity_".$user."(id, icon, activity, time) VALUES (NULL, '".$icon."', '".$key."', '".time()."')");
    $db->close();
	}

	public function value($key)
	{
		global $lang;

		$key = str_replace(".", "_", str_replace("}", "", str_replace("{", "", $key)));

	  return $lang->get($key);
	}
}

?>
