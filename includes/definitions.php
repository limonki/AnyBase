<?php

// Defines root directory.
define('__ROOT__', dirname(dirname(__FILE__)));

require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/exception.php');
require_once(__ROOT__.'/includes/activity.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/installed.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

$redirect = array('main.php', 'profile.php', 'create-table.php', 'edit-table.php', 'delete-table.php', 'relations.php', 'tables.php', 'configuration.php', 'import-export.php');
$select_type = array('INT', 'VARCHAR', 'TEXT', 'DATE', 'TINYINT', 'SMALLINT', 'MEDIUMINT', 'BIGINT', 'DECIMAL', 'FLOAT', 'DOUBLE', 'REAL', 'BOOLEAN', 'TIME', 'TIMESTAMP', 'CHAR', 'TINYTEXT', 'MEDIUMTEXT', 'LONGTEXT', 'BINARY', 'BLOB');
$select_attr = array($lang->get('ATTRIBUTES'), 'BINARY', 'UNSIGNED', 'UNSIGNED ZEROFILL');
$select_index = array($lang->get('INDEX'), 'PRIMARY', 'UNIQUE', 'INDEX', 'FULLTEXT', 'SPATIAL');
$activity = new Activity();
$permission_max_lvl = 7;
$permission_lvl = array();

function downloadUpdate($url)
{
  $zipFile = "../latest.zip"; // Local Zip File Path
  $zipResource = fopen($zipFile, "w");
  // Get The Zip File From Server
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_FAILONERROR, true);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_AUTOREFERER, true);
  curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_FILE, $zipResource);
  $page = curl_exec($ch);
  if(!$page) echo "Error :- ".curl_error($ch);
  curl_close($ch);
  fclose($zipResource);

  /* Open the Zip file */
  $zip = new ZipArchive;
  $extractPath = __ROOT__;
  if($zip->open($zipFile) != "true") echo "Error :- Unable to open the Zip File";
  /* Extract Zip File */
  $zip->extractTo($extractPath);
  $zip->close();

  unlink($zipFile);

  header("refresh:5;url=update.php");
}

function getVersion()
{
  $file = "http://www.anybase.cba.pl/version.txt";

  return explode(', ', file_get_contents($file));
}

function detectMobile()
{
  $useragent=$_SERVER['HTTP_USER_AGENT'];

  if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
  {
    return true;
  }

  return false;
}

function clearCookies()
{
  if(isset($_SERVER['HTTP_COOKIE']))
  {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);

    foreach($cookies as $cookie)
    {
      $parts = explode('=', $cookie);
      $name = trim($parts[0]);

      setcookie($name, '', time()-1000);
      setcookie($name, '', time()-1000, '/');
    }
  }
}

function getRestrictedAccess()
{
  global $config;

  $data = array();

  $db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
  $db->query("SELECT * FROM anybase_restricted");

  array_push($data, '---');
  while($row = $db->fetchArray()) array_push($data, $row[1].': '.$row[2]);

  return $data;
}

function getTablesInfo()
{
  global $config;

  $tables = array();
  $data = array();
  $restricted = array();

  $db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
  $db->query("SELECT * FROM anybase_restricted");

  while($row = $db->fetchAssoc())
  {
    array_push($restricted, $row['table_name'].': '.$row['column_name']);
  }

  $db->query("SHOW TABLES LIKE '".$config['db_database_prfx']."%'");

  while($row = $db->fetchArray()) array_push($tables, $row[0]);

  $i = 0;
  $j = 0;
  $data[$i] = '---';
  $i++;
  foreach($tables as $key => $value)
  {
    $db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$value."'");

    while($row = $db->fetchAssoc())
    {
      $str = str_replace($config['db_database_prfx'], '', $value).': '.$row['COLUMN_NAME'];
      if(!match($str, $restricted))
      {
        $data[$i] = $str;
        $i++;
      }
      $j++;
    }
  }

  return $data;
}

for($i = 1; $i < $permission_max_lvl+1; $i++)
{
  array_push($permission_lvl, $i." - ".convToPermissionStatus($i));
}

function lastActivity($user, $limit = null)
{
  global $config;
  global $activity;

  $db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);

  if(!empty($limit)) $sql = "SELECT * FROM anybase_activity_".$user." ORDER BY id DESC LIMIT ".$limit;
  else $sql = "SELECT * FROM anybase_activity_".$user." ORDER BY id DESC";

  $db->query($sql);

  $last_activity_output = "";

  if(!empty($limit))
  {
    while($data = $db->fetchAssoc())
    {
      $last_activity_output .= "<div class=\"last-activity\"><div class=\"".$data["icon"]."-icon32x32\" style=\"width: 16px; height: 16px;\"></div> <a href=\"return: false;\">".$activity->value($data["activity"])." (".date($config['date'].' '.$config['time'], $data["time"]).")</a></div>";
    }

    if(empty($last_activity_output)) $last_activity_output = "No activity for selected user.";
  }
  else
  {
    if(!isset($_GET["page"]) || empty($_GET["page"])) $curr = 1;
    else $curr = htmlentities($_GET["page"]);

    $per_page = 50;

    $num = $db->numRows();

    $i = ($num - 1) - ($curr - 1)*$per_page;
    $j = 1;
    $end = $i - $per_page;
    if($end < 0) $end = 0;

    $pages = round(($num - 1) / $per_page) + 1;
    $num = 1;

    while($i >= $end)
    {
      $data = $db->fetchAssoc();
      $last_activity_output .= "<div class=\"last-activity\"><div class=\"".$data["icon"]."-icon32x32\" style=\"width: 16px; height: 16px;\"></div> <a href=\"return: false;\">".$activity->value($data["activity"])." (".date($config['date'].' '.$config['time'], $data["time"]).")</a></div>";
      $i--;
      $j++;
    }

    if(empty($last_activity_output)) $last_activity_output = "No activity for selected user.";

    $last_activity_output .= "<div class=\"center\">";
    $last_activity_output .= generatePageLinks($curr, $pages);
    $last_activity_output .= "</div>";
  }

  return $last_activity_output;
}

function generatePageLinks($curr_page, $num_pages)
{
     $page_links = '';

     // odnośnik do poprzedniej strony (-1)
     if($curr_page > 1) {
          $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($curr_page - 1) . '">«</a> ';
     }

     $i = $curr_page - 4;
     $page = $i + 8;

     for($i; $i <= $page; $i++)
     {
          if ($i > 0 && $i <= $num_pages)
          {
               //jeżeli jesteśmy na danej stronie to nie wyświetlamy jej jako link
               if ($curr_page == $i  && $i != 0) $page_links .= '' . $i;
               else
               {
                    //wyświetlamy odnośnik do 1 strony
                    if ($i == ($curr_page - 4) && ($curr_page - 5) != 0) {
                         $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=1">1</a> ';
                    }

                    //wyświetlamy "kropki", jako odnośnik do poprzedniego bloku stron
                    if ($i == ($curr_page - 4) && (($curr_page - 6)) > 0) {
                         $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($curr_page - 5) . '">...</a> ';
                    }

                    //wyświetlamy liki do bieżących stron
                    $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '"> ' . $i . '</a> ';

                    //wyświetlamy "kropki", jako odnośnik do następnego bloku stron
                    if ($i == $page && (($curr_page + 4) - ($num_pages)) < -1) {
                         $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($curr_page + 5) . '">...</a>';
                    }

                    //wyświetlamy odnośnik do ostatniej strony
                    if ($i == $page && ($curr_page + 4) != $num_pages) {
                         $page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?page=' . $num_pages . '">' . $num_pages . '</a> ';
                    }
               }
          }
     }

     //odnośnik do następnej strony (+1)
     if ($curr_page < $num_pages) {
          $page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . ($curr_page + 1) . '">»</a>';
     }

     return $page_links;
}

function lang($language)
{
  global $installed_lang;
  global $acronyms;

  for($i = 0; $i < count($installed_lang); $i++)
  {
    if(strcmp($installed_lang[$i], $language) == 0) return $acronyms[$i];
  }
}

function updateConfiguration($array)
{
  global $config;
  global $lang;

  $path = '../config/config.php';
  $file = file_get_contents($path, FILE_USE_INCLUDE_PATH);

  foreach($array as $key => $value)
  {
    if(!is_numeric($value)) $file = str_replace('$config[\''.$key.'\'] = \''.$config[$key].'\';', '$config[\''.$key.'\'] = \''.$value.'\';', $file);
    else $file = str_replace('$config[\''.$key.'\'] = '.$config[$key].';', '$config[\''.$key.'\'] = '.$value.';', $file);
  }

  if(file_put_contents($path, $file, FILE_USE_INCLUDE_PATH))
  {
    setcookie('title', conv2Send2JS($lang->get('MSG_SUCCESS_TITLE')), 0, '/');
    setcookie('msg', conv2Send2JS($lang->get('MSG_SUCCESS_MSG')), 0, '/');
    setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

    echo "Succedx01";
  }
  else
  {
    setcookie('title', conv2Send2JS($lang->get('MSG_ERROR_TITLE')), 0, '/');
    setcookie('msg', conv2Send2JS($lang->get('MSG_ERROR_MSG')), 0, '/');
    setcookie('close', conv2Send2JS($lang->get('CLOSE')), 0, '/');

    echo "Errorx01";
  }
}

function match($str, $array)
{
  foreach($array as $key => $value)
  {
    if(strcmp($str, $value) == 0) return true;
  }
  return false;
}

function prepareTables2Edit($num)
{
  global $config;

  $db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
  $db->query("SHOW TABLES LIKE '".$config['db_database_prfx']."%'");

  $i = 0;
  $output = 'No tables created in database.';

  while($table = $db->fetchArray())
  {
    if($i == 0) $output = '<div class="edit" style="overflow-x:auto;"><table>';
    if($i%$num == 0) $output .= '<tr>';
    else if($i%$num == $num) $output .= '</tr>';
    $i++;
    $output .= '<td>'.$i.'. <a href="view-table.php?v='.url2hash($table[0]).'">'.str_replace($config['db_database_prfx'], '', $table[0]).'</a> <a class="edit-link" href="return: false;" data-name="'.$table[0].'"><img class="edit-img" src="images/icons/edit16x16.png"></a></td>';
  }
  if($i != 0) $output .= '</table></div>';

  return $output;
}

function prepareTables2Rmvl($num)
{
  global $config;

  $db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
  $db->query("SHOW TABLES LIKE '".$config['db_database_prfx']."%'");

  $i = 0;
  $output = 'No tables created in database.';

  while($table = $db->fetchArray())
  {
    if($i == 0) $output = '<div class="rmvl" style="overflow-x:auto;"><table>';
    if($i%$num == 0) $output .= '<tr>';
    else if($i%$num == $num) $output .= '</tr>';
    $i++;
    $output .= '<td>'.$i.'. <a href="view-table.php?v='.url2hash($table[0]).'">'.$table[0].'</a> <a class="rmv-link" href="return: false;" data-name="'.$table[0].'"><img class="rmv-img" src="images/icons/delete16x16.png"></a></td>';
  }
  if($i != 0) $output .= '</table></div>';

  return $output;
}

function url2hash($url)
{
  return hash('sha256', $url);
}

function checkRedirect($get)
{
  global $redirect;

  foreach($redirect as $key => $value)
  {
    if(strcmp($get, url2hash($value)) == 0) header('Location: '.$value);
  }
}

function conv2Send2JS($msg)
{
  return str_replace(' ', '-', $msg);
}

function generateRandomString($length = 10)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';

  for ($i = 0; $i < $length; $i++)
  {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }

  return $randomString;
}

function fireExceptionMsg($msg, $url_param, $page)
{
  try {
    throw new CustomException($url_param);
  } catch (CustomException $e) {
    $e->redirect($msg, $page);
  }
}

function actualURL()
{
  return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function isSetGet($url_get)
{
  if(isset($_GET[$url_get])) return true;
  else return false;
}

function convToPermissionStatus($perm_lvl)
{
  // view, edit, delete, configure, [r] - restricted - if none then full acces
  // nnnn - no rights
  global $lang;

  switch($perm_lvl)
  {
    case 1:
      return $lang->get('GUEST').' (nnnn)';
    case 2:
      return $lang->get('GUEST').' (v[r]nnn)';
    case 3:
      return $lang->get('USER').' (vnnn)';
    case 4:
      return $lang->get('USER').' (ve[r]nn)';
    case 5:
      return $lang->get('MODERATOR').' (venc)';
    case 6:
      return $lang->get('MODERATOR').' (ved[r]c)';
    case 7:
      return $lang->get('ADMINISTRATOR').' (vedc)';
  }
}

function elementsOutput()
{
  global $element;
  global $elements_output;

  $elements_output = '';
  foreach($element as $value)
  {
    $elements_output .= $value->output();
  }
}

function createThumbnail($image_path, $thumb_path, $new_width, $new_height, $aspect_hold = FALSE)
{
    $path = $image_path;

    $mime = getimagesize($path);

    if($mime['mime'] == 'image/png')   $src_img = imagecreatefrompng($path);
    if($mime['mime'] == 'image/jpg' || $mime['mime'] == 'image/jpeg' || $mime['mime'] == 'image/pjpeg') $src_img = imagecreatefromjpeg($path);

    $old_x = imageSX($src_img);
    $old_y = imageSY($src_img);

    if($aspect_hold)
    {
      if($old_x > $old_y)
      {
          $thumb_w = $new_width;
          $thumb_h = $old_y*($new_height/$old_x);
      }

      if($old_x < $old_y)
      {
          $thumb_w = $old_x*($new_width/$old_y);
          $thumb_h = $new_height;
      }

      if($old_x == $old_y)
      {
          $thumb_w = $new_width;
          $thumb_h = $new_height;
      }
    }
    else
    {
      $thumb_w = $new_width;
      $thumb_h = $new_height;
    }

    $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);

    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);

    // New save location
    $new_thumb_loc = $thumb_path;

    if($mime['mime'] == 'image/png') $result = imagepng($dst_img,$new_thumb_loc,8);
    if($mime['mime'] == 'image/jpg' || $mime['mime'] == 'image/jpeg' || $mime['mime'] == 'image/pjpeg') $result = imagejpeg($dst_img,$new_thumb_loc,80);

    return $result;
}

?>
