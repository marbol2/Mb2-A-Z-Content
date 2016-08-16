<?php


defined('JPATH_PLATFORM') or die;



class JFormFieldMb2startitem extends JFormField
{
	
	
	protected $type = 'Mb2startitem';	
	protected $divid = '';
		
	
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if ($result == true)
		{			
			$divid = (string) $this->element['divid'];
			$this->divid = $divid;
						
		}

		return $result;
	}	

	
	
	protected function getInput()
	{
		
		$output = '';
		
		$output .= '</div></div>';
		
		$output .= '<div id="' . $this->divid . '">';		
		
		$output .= '<div><div>';	
		
		return $output;	
		
		
	}
}
