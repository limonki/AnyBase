<?php

abstract class PanelAdditive
{
  protected $m_image;
  protected $m_text;
  protected $m_link;
  protected $m_onclick;
  protected $m_position;
  protected $m_vertical_align;
  protected $m_img_width;

  public function __construct($image, $text, $link, $onclick, $position, $vertical_align, $img_width = NULL)
  {
    $this->m_image = $image;
    $this->m_text = $text;
    $this->m_link = $link;
    $this->m_onclick = $onclick;
    $this->m_position = $position;
    $this->m_vertical_align = $vertical_align;
    $this->m_img_width = $img_width;
  }
}

class Link extends PanelAdditive
{
  public function output($param = '')
  {
    $output = '<div class="additives"{STYLE}>';
    $style = '';
    switch($this->m_position)
    {
      case 1:
      {
        $style .= 'text-align: center;';
        break;
      }
      case 2:
      {
        $style .= 'float: right;';
        break;
      }
    }
    switch($this->m_vertical_align)
    {
      case 1:
      {
        $style .= 'vertical-align: middle;';
        break;
      }
      case 2:
      {
        $style .= 'vertical-align: top;';
        break;
      }
    }
    if(!empty($param) && strcmp($param, 'all_width') == 0) $style .= 'width: 100%;';
    if($this->m_position != 0 || $this->m_vertical_align != 0) $output = str_replace('{STYLE}', ' style="'.$style.'"', $output);

    if(!empty($param) && strcmp($param, 'img_div') == 0) $output .= '<div>{IMG}</div>';
    else $output .= '{IMG}';

    if(!empty($this->m_img_width)) $output = str_replace('{IMG}', '<img src="'.$this->m_image.'" style="width: '.$this->m_img_width.'px; height: auto;">', $output);
    else $output = str_replace('{IMG}', '<img src="'.$this->m_image.'">', $output);

    $output .= ' <a href="'.$this->m_link.'">'.$this->m_text.'</a></div>';
    return $output;
  }
}

class Button extends PanelAdditive
{
  public function output($param = '')
  {
    $output = '';
    if(!empty($param) && strcmp($param, 'additives') == 0) $output .= '<div class="additives-no-img">';
    $output .= '<div class="panel panel-button"';
    switch($this->m_position)
    {
      case 0:
      {
        $output .= '>';
        break;
      }
      case 1:
      {
        $output .= ' style="text-align: center;">';
        break;
      }
      case 2:
      {
        $output .= ' style="text-align: right;">';
        break;
      }
    }
    $output .= '<a href="'.$this->m_link.'"><button';
    if(!empty($this->m_onclick)) $output .= ' onclick="'.$this->m_onclick.'"';
    $output .= '>{IMG} '.$this->m_text.'</button></a></div>';
    if(!empty($param) && strcmp($param, 'additives') == 0) $output .= '</div>';

    if(!empty($this->m_img_width)) $output = str_replace('{IMG}', '<img src="'.$this->m_image.'" style="width: '.$this->m_img_width.'px; height: auto;">', $output);
    else $output = str_replace('{IMG}', '<img src="'.$this->m_image.'">', $output);

    return $output;
  }
}

class Image extends PanelAdditive
{
  public function output($param = '')
  {
    $output = '<div class="panel image"';
    switch($this->m_position)
    {
      case 0:
      {
        $output .= '>';
        break;
      }
      case 1:
      {
        $output .= ' style="text-align: center;">';
        break;
      }
      case 2:
      {
        $output .= ' style="text-align: right;">';
        break;
      }
    }
    $output .= '{IMG}</div>';

    if(!empty($this->m_img_width)) $output = str_replace('{IMG}', '<img src="'.$this->m_image.'" style="width: '.$this->m_img_width.'px; height: auto;">', $output);
    else $output = str_replace('{IMG}', '<img src="'.$this->m_image.'">', $output);

    return $output;
  }
}

?>
