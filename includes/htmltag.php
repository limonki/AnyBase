<?php

require_once('definitions.php');

class HTMLTagAttribute
{
  private $m_name;
  private $m_value;

  public function __construct($name, $value)
  {
    $this->m_name = $name;
    $this->m_value = $value;
  }

  public function reame($name)
  {
    $this->m_name = $name;
  }

  public function revalue($value)
  {
    $this->m_value = $value;
  }

  public function name()
  {
    return $this->m_name;
  }

  public function value()
  {
    return $this->m_value;
  }
}

class HTMLTag
{
  private $m_name;
  private $m_attr;
  private $m_attr_i;
  private $m_double;
  private $m_additional_text;
  private $m_additional_text_position;

  public function __construct($name, $double, $additional_text = '', $additional_text_position = 0)
  {
    $this->m_name = $name;
    $this->m_attr = array();
    $this->m_double = $double;
    $this->m_additional_text = $additional_text;
    $this->m_additional_text_position = $additional_text_position;
    $this->m_attr_i = 0;
  }

  public function addAttr($name, $value)
  {
    $this->m_attr[$this->m_attr_i] = new HTMLTagAttribute($name, $value);
    $this->m_attr_i++;
  }

  public function changeAttr($name, $value)
  {
    for($i = 0; $i < $this->m_attr_i; $i++)
    {
      if(strcmp($name,  $this->m_attr[$i]->name()) == 0)
      {
        $this->m_attr[$i]->reame($name);
        $this->m_attr[$i]->revalue($value);
      }
    }
  }

  public function delAttr($name)
  {
    for($i = 0; $i < $this->m_attr_i; $i++)
    {
      if(isset($this->m_attr[$i]))
      {
        if(strcmp($name,  $this->m_attr[$i]->name()) == 0)
        {
          unset($this->m_attr[$i]);
          $this->m_attr_i--;
        }
      }
    }
  }

  public function checkAttrExist($name)
  {
    for($i = 0; $i < $this->m_attr_i; $i++)
    {
      if(isset($this->m_attr[$i]))
      {
        if(strcmp($name,  $this->m_attr[$i]->name()) == 0)
        {
          return true;
        }
      }
    }
    return false;
  }

  public function checkName($name)
  {
    if(strcmp($name,  $this->m_name) == 0)
    {
      return true;
    }
    return false;
  }

  public function changeAdditionalText($text)
  {
    $this->m_additional_text = $text;
  }

  public function additionalText()
  {
    return $this->m_additional_text;
  }

  public function open()
  {
    $output = '';
    if(!empty($this->m_additional_text) && $this->m_additional_text_position == 0) $output .= $this->m_additional_text;
    $output .= '<'.$this->m_name;
    if($this->m_attr_i > 0)
    {
      for($i = 0; $i < $this->m_attr_i; $i++)
      {
        if(isset($this->m_attr[$i]))
        {
          $output .= ' '.$this->m_attr[$i]->name().'="'.$this->m_attr[$i]->value().'"';
        }
      }
    }
    $output .= '>';
    if(!empty($this->m_additional_text) && $this->m_additional_text_position == 1) $output .= $this->m_additional_text;

    return $output;
  }

  public function close()
  {
    $output = '</'.$this->m_name.'>';

    return $output;
  }

  public function checkDouble()
  {
    if(strcmp($this->m_double, 'true') == 0)
      return true;
    else return false;
  }

  public function attrValue($name)
  {
    for($i = 0; $i < count($this->m_attr); $i++)
    {
      if(strcmp($this->m_attr[$i]->name(), $name) == 0)
      {
        return $this->m_attr[$i]->value();
      }
    }
  }

  public function name()
  {
    return $this->m_name;
  }
}

?>
