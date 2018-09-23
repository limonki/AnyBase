<?php

require_once('definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/database.php');

class User
{
  protected $m_token;
  private $m_authorized;
  private $m_info;

  public function __construct()
  {
    $this->m_token = generateRandomString(32);
    $this->m_authorized = false;
  }

  public function lvl() {return $this->m_info['permission'];}

  public function hasPermission($permission)
  {
    if($this->m_info['permission'] >= $permission) return true;
    else return false;
  }

  public function authorize($username, $password)
  {
    global $config;

    $db = new DataBase($config['db_server'], $config['db_username'], $config['db_password'], $config['db_database']);
    $db->select('anybase_user', '*', array('username' => $username, 'password' => $password));

    if($db->numRows() == 1)
    {
      $this->m_info = $db->fetchAssoc();
      $this->m_authorized = true;
    }
    else $this->m_authorized = false;

    $db->close();
  }

  public function info()
  {
    $photo = explode('.', $this->m_info['avatar']);
    $this->m_info['avatar_low'] = $photo[0].'32x32.'.$photo[1];
    $this->m_info['avatar_high'] = $photo[0].'64x64.'.$photo[1];
    return $this->m_info;
  }

  public function authorized()
  {
    if(isSetGet('session_expired')) $this->m_authorized = false;
    return $this->m_authorized;
  }
}

?>
