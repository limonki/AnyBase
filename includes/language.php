<?php

require_once('definitions.php');

class Language
{
  private $m_array = array();
  private $m_name;

  public function __construct($name)
  {
    $this->m_name = $name;
  }

  public function add($field, $value)
  {
    $this->m_array[$field] = $value;
  }

  public function get($field = '')
  {
    if(empty($field)) return $this->m_array;
    else return $this->m_array[$field];
  }
}

?>
