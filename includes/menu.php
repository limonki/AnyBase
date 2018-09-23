<?php

require_once('definitions.php');
require_once(__ROOT__.'/includes/htmltag.php');
require_once(__ROOT__.'/includes/htmlstruct.php');

class Menu extends HTMLStruct
{
  private $m_add_menu_element;

  public function __construct($add = false)
  {
    parent::__construct();
    $this->m_add_menu_element = $add;
  }

  public function addElement($name, $link, $icon = null, $link_active = false, $parent_id = null)
  {
    $this->m_element[$this->m_i] = array('id' => $this->m_i+1,
                                         'parent_id' => $parent_id,
                                         'name' => $name,
                                         'link' => $link,
                                         'link.active' => $link_active,
                                         'icon' => $icon,
                                         'tag' => new HTMLTag('li', true));

    $this->m_element[$this->m_i]['tag']->addAttr('id', 'link-'.$this->m_i);

    $this->m_i++;
  }

  private function prepareOutput($menu_array, $ul, $a, $icon)
  {
    $this->m_output .= $ul->open();
    if($ul->checkAttrExist('id')) $ul->delAttr('id');
    foreach($menu_array as $menu)
    {
      if($menu['link.active']) $a->addAttr('class', 'active');
      else $a->delAttr('class');
      $a->changeAttr('href', $menu['link']);
      $icon->changeAttr('src', $menu['icon']);
      if(!empty($menu['icon']))
      {
        $this->m_output .= $menu['tag']->open();
        if(!empty($menu['link'])) $this->m_output .= $a->open();
        $this->m_output .= $icon->open().'<span>'.$menu['name'].'</span>';
      }
      else
      {
        $this->m_output .= $menu['tag']->open();
        if(!empty($menu['link'])) $this->m_output .= $a->open();
        $this->m_output .= '<span>'.$menu['name'].'</span>';
      }

      if(empty($menu['parent_id']) && $this->m_add_menu_element) $this->m_output .= '<div id="element"></div>';

      if(array_key_exists('children', $menu))
      {
        $this->prepareOutput($menu['children'], $ul, $a, $icon);
      }
      if(!empty($menu['link'])) $this->m_output .= $a->close();
      $this->m_output .= $menu['tag']->close();
    }
    $this->m_output .= $ul->close();
  }

  public function generate()
  {
    $ul = new HTMLTag('ul', 'true');
    $a = new HTMLTag('a', 'true');
    $icon = new HTMLTag('img', 'false');

    $a->addAttr('href', null);
    $icon->addAttr('src', null);
    $ul->addAttr('id', 'menu');

    $this->m_array = $this->buildTree($this->m_element);
    $this->prepareOutput($this->m_array, $ul, $a, $icon);
    return $this->m_output;
  }
}

?>
