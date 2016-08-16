<?php


defined('JPATH_PLATFORM') or die;


class JFormFieldMb2size extends JFormField
{
	
	protected $type = 'Mb2size';
	protected $defsize;
	
	
	public function __get($name)
	{
		switch ($name)
		{
			case 'defsize':
				return $this->$name;
		}

		return parent::__get($name);
	}

	
	
	public function __set($name, $value)
	{
		switch ($name)
		{			

			case 'defsize':
				$value = (string) $value;
				$value = ($value == $name || $value == 'true' || $value == '1');

			default:
				parent::__set($name, $value);
		}
	}

	
	
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if ($result == true)
		{			
			$defsize = (string) $this->element['defsize'];
			$this->defsize = $defsize;			
		}

		return $result;
	}

	
	protected function getInput()
	{

		$defarr = explode(',', $this->defsize);
		
		$style = ' style="width:35px;"';	
		
		$wval = (isset($this->value['w']) && $this->value['w']!='') ? $this->value['w'] : $defarr[0];
		$hval = (isset($this->value['h']) && $this->value['h']!='') ? $this->value['h'] : $defarr[1];	
		$suff = isset($defarr[2]) ? $defarr[2] : '';			
		
		$html[] = '<style type="text/css">.mb2size p{float:left;margin:0 15px 0 0;}</style>';
		
		
		$html[] = '<div class="mb2size clearfix">';
		$html[] = '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="" />';
		$html[] = '<p>' . JText::_('MB2_FIELD_WIDTH') . ':<input type="text" name="' . $this->name . '[w]" id="' . $this->id . '_w" value="' . $wval . '"' . $style. ' />' . $suff . '</p>';
		$html[] = '<p>' . JText::_('MB2_FIELD_HEIGHT') . ':<input type="text" name="' . $this->name . '[h]" id="' . $this->id . '_h" value="' . $hval . '"' . $style. ' />' . $suff  . '</p>';
		$html[] = '</div>';

		return implode($html);
	}

	
}
