<?php

require_once("../includes/definitions.php");
require_once(__ROOT__."/config/config.php");
require_once(__ROOT__."/includes/session.php");

$session = new Session();

$avatar_high = htmlentities($_POST['avatar_high'], ENT_QUOTES);
$avatar_low = htmlentities($_POST['avatar_low'], ENT_QUOTES);
$max_size = htmlentities($_POST['max_size'], ENT_QUOTES);
$create_miniature = htmlentities($_POST['create_miniature'], ENT_QUOTES);

if(is_uploaded_file($_FILES['file']['tmp_name']))
{
  if(!empty($avatar_high))
  {
    move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/AnyBase/admin/'.$avatar_high);
    createThumbnail(__ROOT__.'/'.$avatar_high, __ROOT__.'/'.$avatar_low, 32, 32);
    createThumbnail(__ROOT__.'/'.$avatar_high, __ROOT__.'/'.$avatar_high, 64, 64);

    setcookie('title', conv2Send2JS($lang->get('MSG_SEND_SUCCESS_TITLE')), 0, '/');
    setcookie('msg', conv2Send2JS($lang->get('MSG_SEND_SUCCESS_MSG')), 0, '/');
    setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

    echo 'Succedx01';
  }
  else move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/AnyBase/admin/'.$_FILES['file']['name']);
  //header("Location: ".$redirect."&file=".$_FILES['file']['name']);
}
else
{
  setcookie('title', conv2Send2JS($lang->get('MSG_SEND_ERROR_TITLE')), 0, '/');
  setcookie('msg', conv2Send2JS($lang->get('MSG_SEND_ERROR_MSG')), 0, '/');
  setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

  echo 'Errorx01';
  //header("Location: ".$redirect."&file=error");
}

?>
