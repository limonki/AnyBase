<?php

require_once("../includes/definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__.'/includes/activity.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/session.php');

$session = new Session();

$email = htmlentities($_POST["email"], ENT_QUOTES);

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);

$db->query("SELECT * FROM anybase_user WHERE email='$email';");
$exist_email = $db->numRows();

if($exist_email != 0)
{
  $link = url2hash(generateRandomString());
  $user = $db->fetchAssoc();

  $tmp = explode("htdocs", __ROOT__);

  $to = $user['email'];

  $subject = $config['project_name'].' - '.$lang->get("FORGOTTEN_FORGOTTEN");

  $headers = "From: ".strip_tags($config['support_contact_email'])."\r\n";
  $headers .= "Reply-To: ".strip_tags($config['support_contact_email'])."\r\n";
  //$headers .= "CC: susan@example.com\r\n"; // copy of an email sent to ...
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

  $message  = '<html><body style="font-family: Tahoma; color: #5e5e5e;">';
  $message .= '<img style="padding-bottom: 32px;" src="http://localhost/AnyBase/admin/images/icon.png">';
  $message .= '<div style="padding: 0 0 0 32px;">';
  $message .= '<div style="font-size: 24px;">'.$lang->get("FORGOTTEN_EMAIL_WELCOME")." ".$user['firstname'].",</div><br>";
  $message .= '<div style="font-size: 16px;">'.str_replace("{ACCOUNT}", '<a href="mailto:'.$user['email'].'">'.$user['email']."</a>", $lang->get("FORGOTTEN_EMAIL_TEXT1"))."</div><br><br>";
  $message .= '<div style="font-size: 16px;">'.$lang->get("FORGOTTEN_EMAIL_TEXT2")."</div><br><br>";
  $message .= '<a style="text-decoration: none; color: #ffffff;" href="'.str_replace("\\", "/", $_SERVER['SERVER_NAME'].$tmp[1]."/forgotten.php?v=".$link).'"><span style="font-size: 24px; background: #5cc60f; padding: 15px; border-radius: 5px;">'.$lang->get("FORGOTTEN_EMAIL_BUTTON")."</span></a><br><br><br>";
  $message .= '<div style="font-size: 16px;">'.$lang->get("FORGOTTEN_EMAIL_TEXT3")."</div><br><br>";
  $message .= '<div style="font-size: 16px;">'.$lang->get("FORGOTTEN_EMAIL_BYE")."</div><br>";
  $message .= '</div>';
  $message .= "</body></html>";

  if(mail($to, $subject, $message, $headers))
  {
    $db->query("INSERT INTO anybase_reset_pass(id, link, username) VALUES (NULL, '".$link."', '".$user['username']."')");

    setcookie('title', conv2Send2JS($lang->get('MSG_FORG_SUCC_TITLE')), 0, '/');
    setcookie('msg', conv2Send2JS($lang->get('MSG_FORG_SUCC_MSG')), 0, '/');
    setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

    echo 'Succedx01';
  }
  else
  {
    setcookie('title', conv2Send2JS($lang->get('MSG_FORG_ERR_TITLE')), 0, '/');
    setcookie('msg', conv2Send2JS($lang->get('MSG_FORG_ERR_MSG')), 0, '/');
    setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

    echo 'Errorx01';
  }
}
else echo 'Errorx02';

echo $db->error();

$db->close();

?>
