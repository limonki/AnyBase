<?php

require_once('definitions.php');

/**
  * Custom exception class.
  *
  * @remarks A simple exception class.
  * @author Balcerzak Mateusz
  * @version 1.0
  * @since 1.0
  * @copyright Copyright (c) 2018, Balcerzak Mateusz
  *
  **/
class CustomException extends Exception
{
  /**
    * Returns error message.
    *
    * @param none
    * @return void
    **/
  public function redirect($msg, $page, $additional_get = '')
  {
    setcookie('msg', conv2Send2JS($msg), 0, '/');

    $header = '';

    if(!empty($page)) $header .= 'Location: '.$page.'?'.$this->getMessage();
    else 'Location: ?'.$this->getMessage();

    if(!empty($additional_get)) $header .= '&'.$additional_get;

    header($header);
  }

  /**
    * Returns error message.
    *
    * @param none
    * @return string $error_msg String identifier.
    **/
  public function errorMessage()
  {
    // Error message.
    $error_msg = 'Error on line '.$this->getLine().' in '.$this->getFile().': <b>'.$this->getMessage().'</b>.';
    return $error_msg;
  }
}

?>
