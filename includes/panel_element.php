<?php

require_once('definitions.php');
require_once(__ROOT__.'/config/config.php');
require_once(__ROOT__.'/includes/panel_element_additive.php');

$panel_id = 0;

class PanelElement
{
  private $m_name;
  private $m_title;
  private $m_description;
  private $m_body;
  private $m_foot;
  private $m_type;
  private $m_params;
  private $m_additive;
  private $m_output;
  private $m_keys;

  public function __construct($name, $title, $description, $body, $foot, $type, $params)
  {
    $this->m_name = $name;
    $this->m_title = $title;
    $this->m_description = $description;
    $this->m_body = $body;
    $this->m_foot = $foot;
    $this->m_type = $type;
    $this->m_params = $params;
    $this->m_output = '';

    $this->m_body = str_replace('{ADDITIVE}','<div>{ADDITIVE}</div>', $this->m_body);
    $this->m_foot = str_replace('{ADDITIVE}','<div>{ADDITIVE}</div>', $this->m_foot);
  }

  public function executeCallback($callback)
  {
    return call_user_func($callback);
  }

  public function createAdditive($type, $f_or_b, $image, $text, $link, $onclick, $position, $vertical_align, $param = '', $img_width = NULL)
  {
    if(strcmp($type, 'link') == 0) $additive = new Link($image, $text, $link, $onclick, $position, $vertical_align, $img_width);
    else if(strcmp($type, 'button') == 0) $additive = new Button($image, $text, $link, $onclick, $position, $vertical_align, $img_width);
    else if(strcmp($type, 'image') == 0) $additive = new Image($image, $text, $link, $onclick, $position, $vertical_align, $img_width);

    if(strcmp($f_or_b, 'body') == 0) $this->addAdditiveBody($additive, $param);
    else if(strcmp($f_or_b, 'foot') == 0) $this->addAdditiveFoot($additive, $param);
  }

	public function addKey($key, $value)
  {
		$this->m_keys[strtoupper($key)] = $value;
	}

	public function init()
  {
		if(count($this->m_keys) > 0)
		{
			foreach($this->m_keys as $key => $value)
      {
        $this->m_title = str_replace('{' . $key . '}', $value, $this->m_title);
        $this->m_description  = str_replace('{' . $key . '}', $value, $this->m_description);
        $this->m_body = str_replace('{' . $key . '}', $value, $this->m_body);
        $this->m_foot = str_replace('{' . $key . '}', $value, $this->m_foot);
      }
		}
	}

  public function generate()
  {
    global $panel_id;
    $panel_id++;

    $this->m_body = str_replace('{ADDITIVE}', '', $this->m_body);
    $this->m_foot = str_replace('{ADDITIVE}', '', $this->m_foot);

    $this->m_output .= '<div id="panel-id-'.$panel_id.'" class="col-'.$this->m_params['col'].'"><div class="panel panel-'.$this->m_type.' shadow">';

    if(!empty($this->m_title))
    {
      if(array_key_exists('title_color', $this->m_params)) $this->m_output .= '<div class="panel title '.$this->m_params['title_color'].'">'.$this->m_title.'</div>';
      else
      {
        if(strcmp($this->m_type, 'default') == 0) $this->m_output .= '<div class="panel title">'.$this->m_title.'</div>';
        else $this->m_output .= '<div class="panel title" style="border-bottom: 1px solid #ececec;">'.$this->m_title.'</div>';
      }
    }
    if(!empty($this->m_description))
    {
      if(array_key_exists('text_color', $this->m_params)) $this->m_output .= '<div class="panel description '.$this->m_params['text_color'].'">'.$this->m_description.'</div>';
      else $this->m_output .= '<div class="panel description">'.$this->m_description.'</div>';
    }
    if(!empty($this->m_body))
    {
      if(array_key_exists('text_color', $this->m_params)) $this->m_output .= '<div class="panel text '.$this->m_params['text_color'].'">'.$this->m_body.'</div>';
      else $this->m_output .= '<div class="panel text">'.$this->m_body.'</div>';
    }
    if(!empty($this->m_foot))
    {
      if(array_key_exists('foot_color', $this->m_params)) $this->m_output .= '<div class="panel foot foot-'.$this->m_params['foot_color'].'">'.$this->m_foot.'</div>';
      else $this->m_output .= '<div class="panel foot">'.$this->m_foot.'</div>';
    }

    $this->m_output .= '</div></div>';

    if(array_key_exists('reset', $this->m_params))
      if($this->m_params['reset'] == true) $this->m_output .= '<div class="col-18 restore-panel-line"></div>';
  }

  public function output()
  {
    $this->generate();
    return $this->m_output;
  }

  public function addAdditiveBody($elem, $param = '')
  {
    $this->m_body = str_replace('{ADDITIVE}', $elem->output($param).'{ADDITIVE}', $this->m_body);
  }

  public function addAdditiveFoot($elem, $param = '')
  {
    $this->m_foot = str_replace('{ADDITIVE}', $elem->output($param).'{ADDITIVE}', $this->m_foot);
  }
}

?>
