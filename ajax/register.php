<?php

require_once("../includes/definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__.'/includes/activity.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/session.php');

$session = new Session();

if(isset($_POST["v"])) $v = htmlentities($_POST["v"], ENT_QUOTES);
$firstname = htmlentities($_POST["firstname"], ENT_QUOTES);
$lastname = htmlentities($_POST["lastname"], ENT_QUOTES);
$username = htmlentities($_POST["username"], ENT_QUOTES);
$password = url2hash(htmlentities($_POST["password"], ENT_QUOTES));
$email = htmlentities($_POST["email"], ENT_QUOTES);
$bio = htmlentities($_POST["bio"], ENT_QUOTES);
$avatar = "images/avatars/".$username.".png";
$permission = 1;

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);

$file[0] = '../images/avatars/default64x64.png';
$file[1] = '../images/avatars/default32x32.png';
$newfile[0] = '../images/avatars/'.$username.'64x64.png';
$newfile[1] = '../images/avatars/'.$username.'32x32.png';

if(copy($file[0], $newfile[0]) && copy($file[1], $newfile[1]))
{
  if(!empty($v))
  {
    $db->query("SELECT * FROM anybase_reg_links WHERE link='$v';");
    $row = $db->fetchAssoc();
    $tmp = explode(" - ", $row["permission"]);
    $permission = $tmp[0];
    if(empty($permission)) $permission = 2;
    $db->query("DELETE FROM anybase_reg_links WHERE link='$v';");
  }
  $db->query("SELECT * FROM anybase_user WHERE username='$username';");
  $exist_username = $db->numRows();
  $db->query("SELECT * FROM anybase_user WHERE email='$email';");
  $exist_email = $db->numRows();

  if($exist_username == 0 && $exist_email == 0)
  {
    $query_1 = $db->query("CREATE TABLE `anybase_activity_".$username."`(`id` int(11) NOT NULL AUTO_INCREMENT, `icon` text NOT NULL, `activity` text NOT NULL, `time` text NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    $query_2 = $db->query("INSERT INTO anybase_user(id, username, password, email, firstname, lastname, bio, avatar, permission) VALUES (NULL, '$username', '$password', '$email', '$firstname', '$lastname', '$bio', '$avatar', $permission);");

    if($query_1 && $query_2)
    {
      setcookie('title', conv2Send2JS($lang->get('MSG_REG_SUCC_TITLE')), 0, '/');
      setcookie('msg', conv2Send2JS($lang->get('MSG_REG_SUCC_MSG')), 0, '/');
      setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

      echo 'Succedx01';
    }
    else
    {
      setcookie('title', conv2Send2JS($lang->get('MSG_REG_ERR_TITLE')), 0, '/');
      setcookie('msg', conv2Send2JS($lang->get('MSG_REG_ERR_MSG')), 0, '/');
      setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

      echo 'Errorx01';
    }
  }
}
else
{
  setcookie('title', conv2Send2JS($lang->get('MSG_REG_ERR_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_REG_ERR_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo 'Errorx01';
}

if($exist_username != 0) echo 'Errorx02';
if($exist_email != 0) echo 'Errorx03';

$db->close();

?>
