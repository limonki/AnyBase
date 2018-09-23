<?php

abstract class HTMLStruct
{
    protected $m_array;
    protected $m_element;
    protected $m_i;
    protected $m_output;

    public function __construct()
    {
      $this->m_element = array();
      $this->m_i = 0;
      $this->m_output = null;
    }

    public function buildTree($array, $parent_id = null)
    {
        $tree = array();
        foreach($array as $item)
        {
            if($item['parent_id'] == $parent_id)
            {
                $tree[$item['id']] = $item;

                $children = $this->buildTree($array, $item['id']);
                if($children)
                {
                    $tree[$item['id']]['children'] = $children;
                }
            }
        }
        return $tree;
    }

    public function addElementAttr($id, $name, $value)
    {
      $attr = explode(', ', $name);
      $val = explode(', ', $value);

      for($i = 0; $i < count($attr); $i++)
      {
        if($this->m_element[($id - 1)]['tag']->checkAttrExist($attr[$i]))
        {
          $this->m_element[($id - 1)]['tag']->changeAttr($attr[$i], $val[$i]);
        }
        else $this->m_element[($id - 1)]['tag']->addAttr($attr[$i], $val[$i]);
      }
    }

    public function elementAttrValue($id, $name)
    {
      return $this->m_element[($id - 1)]['tag']->attrValue($name);
    }

    public function get()
    {
      return $this->m_output;
    }

    public function show()
    {
      print $this->m_output;
    }
}

?>
