<?php

require_once('definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/exception.php');
require_once(__ROOT__.'/includes/user.php');
require_once(__ROOT__.'/includes/language.php');
require_once(__ROOT__.'/languages/'.lang($config['language']).'.php');

// Declare exceptions.
class InvalidArgumentTypeException extends CustomException {}
class SessionExpiredException extends CustomException {}

/**
  * Session class.
  *
  * @remarks A simple session wrapper class.
  * @author Balcerzak Mateusz
  * @version 1.0
  * @since 1.0
  * @copyright Copyright (c) 2018, Balcerzak Mateusz
  *
  **/
class Session
{
  /**
    * Session expiration, name, limit, path, domain and security.
    *
    * @var $m_expires $m_config
    **/
  protected $m_expires;
  protected $m_config = array();

  /**
    * Constructor.
    *
    * @param string $name $path String identifier.
    * @param int $limit Integer.
    * @param mixed $domain $secure
    * @return void
    **/
  public function __construct($name = 'PHPSESSID', $limit = 0, $path = '/', $domain = null, $secure = null, $redirect = 'index.php')
  {
    global $config;
    $this->m_expires = $config['session_expires'];
    $this->m_config['name'] = $name;
    $this->m_config['limit'] = $limit;
    $this->m_config['path'] = $path;
    $this->m_config['domain'] = $domain;
    $this->m_config['secure'] = $secure;
    $this->m_config['page_redirect'] = $redirect;
    self::start();
    self::expires();
  }

  /**
    * Prevents session hijacking.
    *
    * @param none
    * @return true/false.
    **/
  protected function preventHijacking()
  {
    // Check hijacking states.
  	if(!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent'])) return false;
    if($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR']) return false;
    if($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']) return false;

  	return true;
  }

  /**
    * Checks if key exists in current session.
    *
    * @param string $key String identifier.
    * @return true/false.
    * @throws InvalidArgumentTypeException Session key is not a string value.
    **/
  public function exist($key)
  {
    global $lang;

    try {
      // Check if key is a string.
      if(!is_string($key))
      {
        throw new InvalidArgumentTypeException('session_key_not_string');
      }
      // Checks if key exists.
      if(isset($_SESSION[$key])) return true;

      return false;
    } catch (InvalidArgumentTypeException $e) {
      $e->redirect($lang->get('SESSION_KEY_NOT_STRING'));
    }
  }

  /**
     * Inserts a value to the current session data.
     *
     * @param string $key String identifier.
     * @param mixed $value Single value or array of values to be inserted.
     * @return mixed Value or array of inserted values.
     * @throws InvalidArgumentTypeException Session key is not a string value.
     */
  public function insert($key, $value)
  {
    global $lang;

    try {
      // Check if key is a string.
      if(!is_string($key))
      {
        throw new InvalidArgumentTypeException('session_key_not_string');
      }
      // Insert value into session.
      $_SESSION[$key] = $value;

      return $value;
    } catch (InvalidArgumentTypeException $e) {
      $e->redirect($lang->get('SESSION_KEY_NOT_STRING'));
    }
  }

  /**
    * Gets a specific value from the current session data.
    *
    * @param string $key String identifier.
    * @param boolean $child Optional child identifier for accessing array elements.
    * @return mixed Returns a string value when succeed or false when failed.
    * @throws InvalidArgumentTypeException Session key is not a string value.
    **/
  public function get($key, $child = false)
  {
    global $lang;

    try {
      // Check if key is a string.
      if(!is_string($key))
      {
        throw new InvalidArgumentTypeException('session_key_not_string');
      }

      // Check if key is alerady set.
      if(isset($_SESSION[$key]))
      {
        // If there is no child simply return key otherwise return child.
        if($child == false) return $_SESSION[$key];
        else
        {
          if(isset($_SESSION[$key][$child]))
          {
            return $_SESSION[$key][$child];
          }
        }
      }
    } catch (InvalidArgumentTypeException $e) {
      $e->redirect($lang->get('SESSION_KEY_NOT_STRING'));
    }
  }

  /**
    * Deletes key from the current session data.
    *
    * @param string $key String identifying the array key to delete.
    * @return void
    * @throws InvalidArgumentTypeException Session key is not a string value.
    **/
    public function delete($key)
    {
      global $lang;

      try {
        // Check if key is a string.
        if(!is_string($key))
        {
          throw new InvalidArgumentTypeException('session_key_not_string');
        }

        unset($_SESSION[$key]);
      } catch (InvalidArgumentTypeException $e) {
        $e->redirect($lang->get('SESSION_KEY_NOT_STRING'));
      }
    }

  /**
    * Regenerates session.
    *
    * @param none
    * @return void
    **/
  function regenerate()
  {
  	// Create new session without destroying the old one.
  	session_regenerate_id(false);

  	// Grab current session ID and close both sessions to allow other scripts to use them.
  	$newSession = session_id();
  	session_write_close();

  	// Set session ID to the new one, and start it back up again.
  	session_id($newSession);
  	session_start();
  }

  /**
    * Checks if session is valid (has it expired?).
    *
    * @param none
    * @return true/false
    * @throws SessionExpiredException Session has expired.
    **/
  protected function expired()
  {
    global $lang;

    try {
      if($this->exist('expires'))
      {
        if($this->m_expires > 0)
        {
          if(time() > $_SESSION['expires'])
          {
              throw new SessionExpiredException('session_expired');
              return true;
          }
        }
      }
      else throw new SessionExpiredException('session_expired');
    } catch (SessionExpiredException $e) {
      $url = explode('/', actualURL());
      $url = explode('?', end($url));
      $url = array_values($url)[0];
      $e->redirect($lang->get('SESSION_EXPIRED'), $this->m_config['page_redirect'], 'v='.url2hash($url));
    }
    return false;
  }

  public function expires()
  {
    self::insert('expires', time() + $this->m_expires);
  }

  /**
    * Starts session.
    *
    * @param none
    * @return void
    **/
  private function start()
  {
  	// Set the cookie name.
  	session_name($this->m_config['name']);

  	// Set SSL level.
  	$https = isset($this->m_config['secure']) ? $this->m_config['secure'] : isset($_SERVER['HTTPS']);

  	// Set session cookie options.
  	session_set_cookie_params($this->m_config['limit'], $this->m_config['path'], $this->m_config['domain'], $https, true);
  	session_start();

  	// Make sure the session hasn't expired, and destroy it if it has.
    if(!self::expired())
  	{
  		// Check to see if the session is new or a hijacking attempt.
  		if(!self::preventHijacking())
  		{
  			// Reset session data and regenerate id.
  			$_SESSION = array();
  			$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
  			$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
  			self::regenerate();

  		// Give a 5% chance of the session id changing on any request.
      } elseif(rand(1, 100) <= 5) self::regenerate();
  	}
    else self::destroy();
  }

  /**
    * Destroys session.
    *
    * @param
    * @return void
    **/
  public function destroy()
  {
    // Destroy session.
    $_SESSION = array();
    session_destroy();
  }
}

?>
