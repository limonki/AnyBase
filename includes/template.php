<?php

require_once('definitions.php');

class Template
{
	private $m_config;
  private $m_output;

	public function __construct($template, $path = './', $type = 'tpl')
  {
		$this->m_config['template'] = $template;
		$this->m_config['keys'] = array();
		$this->m_config['path'] = $path;
		$this->m_config['type'] = $type;
	}

	public function add($key, $value)
  {
		$this->m_config['keys'][strtoupper($key)] = $value;
	}

	public function _init()
  {
		$path = $this->m_config['path'].$this->m_config['template'].'.'.$this->m_config['type'];
		$file = fopen($path, 'r');
		$source = fread($file, filesize($path));
		fclose($file);
		if($source)
    {
			if(count($this->m_config['keys']) > 0)
			{
				foreach($this->m_config['keys'] as $key => $value) $source = str_replace('{' . $key . '}', $value, $source);
			}
      $this->m_output = $source;
			return true;
		}
    else return false;
	}

  public function output()
  {
    return $this->m_output;
  }

  public function show()
  {
		print $this->m_output;
  }
}

?>
