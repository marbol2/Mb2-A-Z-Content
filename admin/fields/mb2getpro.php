<?php


defined('JPATH_PLATFORM') or die;



class JFormFieldMb2getpro extends JFormField
{
	
	
	protected $type = 'Mb2getpro';	
	protected $link = '';
	protected $text = '';
		
	
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if ($result == true)
		{			
			$link = (string) $this->element['link'];
			$this->link = $link;
						
		}		
		
		if ($result == true)
		{			
			$text = (string) $this->element['text'];
			$this->text = $text;
						
		}

		return $result;
	}	

	
	
	protected function getInput()
	{
		
		$output = '';
		
		$output .= '<p><a href="' . $this->link . '" target="_blank">' . $this->text . '</a></p>';	
		
		return $output;	
		
		
	}
}
