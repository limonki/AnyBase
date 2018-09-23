<?php

require_once('definitions.php');
require_once(__ROOT__.'/includes/htmltag.php');
require_once(__ROOT__.'/includes/htmlstruct.php');

class Form extends HTMLStruct
{
  public function __construct()
  {
    parent::__construct();
  }

  public function addElement($name, $double, $parent_id = null, $additional_text = '', $additional_text_position = 0, $break_line = false)
  {
    $this->m_element[$this->m_i] = array('id' => $this->m_i+1,
                                         'parent_id' => $parent_id,
                                         'tag' => new HTMLTag($name, $double, $additional_text, $additional_text_position),
                                         'break_line' => $break_line);

    if(strcmp($name, 'form') == 0) $this->addElementAttr($this->m_i+1, 'onSubmit', 'return false;');

    $this->m_i++;
  }

  public function createOption($array, $elem, $selected = null)
  {
    $i = 1;
    foreach($array as $key => $value)
    {
      self::addElement('option', 'true', $elem, $value, 1, false);
      self::addElementAttr($elem+$i, 'data-index', $i);
      $i++;
    }
    if(!empty($selected)) $this->addElementAttr($elem+$selected, 'selected, style', ', font-weight: bold');

    return count($array);
  }

  public function countElement()
  {
    return count($this->m_element);
  }

  public function checkElementAttrExist($id, $name)
  {
    if($this->m_element[($id - 1)]['tag']->checkAttrExist($name))
    {
      return true;
    }
    else return false;
  }

  public function elementAdditionalText($id)
  {
    return $this->m_element[($id - 1)]['tag']->additionalText();
  }

  public function checkElementName($id, $name)
  {
    if($this->m_element[($id - 1)]['tag']->checkName($name))
    {
      return true;
    }
    else return false;
  }

  public function elementAttrValue($id, $name)
  {
    return $this->m_element[($id - 1)]['tag']->attrValue($name);
  }

  public function changeElementAttr($id, $name, $value)
  {
    $attr = explode(', ', $name);
    $val = explode(', ', $value);

    for($i = 0; $i < count($attr); $i++)
    {
      $this->m_element[($id - 1)]['tag']->changeAttr($attr[$i], $val[$i]);
    }
  }

  public function delElementAttr($id, $name)
  {
    $attr = explode(', ', $name);

    for($i = 0; $i < count($attr); $i++)
    {
      $this->m_element[($id - 1)]['tag']->delAttr($attr[$i]);
    }
  }

  public function changeElementAdditionalText($id, $text)
  {
    $this->m_element[($id - 1)]['tag']->changeAdditionalText($text);
  }

  public function elementName($id)
  {
    return $this->m_element[($id - 1)]['tag']->name();
  }

  private function prepare($form_array)
  {
    foreach($form_array as $form)
    {
      $this->m_output .= $form['tag']->open();
      if(array_key_exists('children', $form))
      {
        $this->prepare($form['children']);
      }
      if($form['tag']->checkDouble()) $this->m_output .= $form['tag']->close();
      if($form['break_line']) $this->m_output .= '<br>';
    }
  }

  public function generate()
  {
    $this->m_array = $this->buildTree($this->m_element);
    $this->prepare($this->m_array);
  }
}

?>
