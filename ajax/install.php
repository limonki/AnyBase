<?php

require_once("../includes/definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__.'/includes/activity.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/session.php');

$db_server = htmlentities($_POST['db_server'], ENT_QUOTES);
$db_username = htmlentities($_POST['db_username'], ENT_QUOTES);
$db_password = htmlentities($_POST['db_password'], ENT_QUOTES);
$db_database = htmlentities($_POST['db_database'], ENT_QUOTES);
$db_database_prfx = htmlentities($_POST['db_database_prfx'], ENT_QUOTES);
$session_expires = htmlentities($_POST['session_expires'], ENT_QUOTES);
$support_contact_email = htmlentities($_POST['support_contact_email'], ENT_QUOTES);

$array = array(
  'db_server' => $db_server,
  'db_username' => $db_username,
  'db_password' => $db_password,
  'db_database' => $db_database,
  'db_database_prfx' => $db_database_prfx,
  'session_expires' => $session_expires,
  'support_contact_email' => $support_contact_email
);

$tables = array(
'CREATE TABLE `anybase_user` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `username` tinytext NOT NULL,
 `password` tinytext NOT NULL,
 `email` tinytext NOT NULL,
 `firstname` tinytext NOT NULL,
 `lastname` tinytext NOT NULL,
 `bio` text NOT NULL,
 `avatar` tinytext NOT NULL,
 `permission` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;',

'CREATE TABLE `anybase_restricted` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `table_name` text NOT NULL,
 `column_name` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8',

'CREATE TABLE `anybase_reset_pass` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `link` text NOT NULL,
 `username` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8',

'CREATE TABLE `anybase_reg_links` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `link` text NOT NULL,
 `permission` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8',

'CREATE TABLE `anybase_backup` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `last` datetime NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8',

'CREATE TABLE `anybase_status` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `installed` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8'
);

$db = @new DataBase($db_server, $db_username, $db_password, '');
$db->setDb($db_database);
if(!$db->selected())
{
  $sql = 'CREATE DATABASE '.$db_database;
  if($db->query($sql))
  {
    $db->selected();
    @$db->query("SELECT * FROM anybase_status");

    if(!$data = @$db->fetchAssoc())
    {
      updateConfiguration($array);

      foreach($tables as $key => $value)
      {
        $query = $db->query($value);
        if(!$query)
        {
          if(strpos($db->error(), 'Table exists') !== false) echo 'Errorx02';
          echo $db->error();
        }
      }

      $query = $db->query("INSERT INTO anybase_status(id, installed) VALUE (NULL, 'yes')");
      if(!$query)
      {
        echo 'Errorx02';
        echo $db->error();
      }

      $level = 7;
      $link = url2hash(generateRandomString());
      $tmp = explode("htdocs", __ROOT__);
      $query = $db->query("INSERT INTO anybase_reg_links(id, link, permission) VALUES (NULL, '".$link."', ".$level.")");
      if(!$query)
      {
        echo 'Errorx03';
        echo $db->error();
      }
      else echo "register.php?v=".$link."Succedx02";
    }
    else
    {
      echo 'Errorx04';
    }
  }
  else
  {
    echo 'Errorx04';
  }
}

?>
