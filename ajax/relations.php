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

$relations = $_POST['relations'];
$tables = $_POST['tables'];
if(isset($_POST['relations_to_del'])) $relations_to_del = $_POST['relations_to_del'];
$all_keys = array();

$db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);

$db->query("SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_TYPE = 'FOREIGN KEY'");
while($row = $db->fetchAssoc()) array_push($all_keys, $row["CONSTRAINT_NAME"]);

foreach($relations as $key => $value)
{
  if(isset($relations_to_del[$key]) && strcmp($relations_to_del[$key], "true") == 0)
  {
    $keys = explode("<->", $relations[$key]);
    $tabs = explode(":", $tables[$key]);

    if(in_array("FK_".$keys[0], $all_keys))
    {
      $sql = "ALTER TABLE ".$config['db_database_prfx'].$tabs[0];

      $sql .= " DROP FOREIGN KEY FK_".$keys[0].", DROP INDEX FK_".$keys[0].";";

      //echo $sql."\n";

      $db->query($sql);
      echo $db->error();
    }
  }
  else
  {
    $keys = explode("<->", $relations[$key]);
    $tabs = explode(":", $tables[$key]);

    if(!in_array("FK_".$keys[0], $all_keys))
    {
      $sql = "ALTER TABLE ".$config['db_database_prfx'].$tabs[0];

      $sql .= " ADD CONSTRAINT FK_".$keys[0]." FOREIGN KEY (".$keys[0].") REFERENCES ".$config['db_database_prfx'].$tabs[1]." (".$keys[1].");";

      //echo $sql."\n";

      $db->query($sql);
      echo $db->error();
    }
  }
}

setcookie('title-succ', conv2Send2JS($lang->get('MSG_RELATIONS_SUCC_TITLE')), 0, '/');
setcookie('msg-succ', conv2Send2JS($lang->get('MSG_RELATIONS_SUCC_MSG')), 0, '/');
setcookie('title-err', conv2Send2JS($lang->get('MSG_RELATIONS_ERR_TITLE')), 0, '/');
setcookie('msg-err', conv2Send2JS($lang->get('MSG_RELATIONS_ERR_MSG')), 0, '/');
setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

$db->close();

?>
