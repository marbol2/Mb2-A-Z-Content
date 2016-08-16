<?php

defined('_JEXEC') or die;



class JFormFieldMb2color extends JFormField
{
	
	
	protected $type = 'Mb2color';

	
	
	protected function getInput()
	{
		
		$output = '';	
		
		
		// Get module name
		$mod = JFormFieldMb2color::getModuleName();
		
		// Load js color script
		$doc = JFactory::getDocument();
		$doc->addStylesheet(JURI::root(true) . '/modules/' . $mod . '/admin/fields/mb2color/css/spectrum.css');	
		$doc->addScript(JURI::root(true) . '/modules/' . $mod . '/admin/fields/mb2color/js/spectrum.js');
		
		
		$css = 'input#' . $this->id;
		$css .= '{';
		$css .= 'display:none!important;';
		$css .= '}';
		
		$doc->addStyleDeclaration($css);
		
		$js = 'jQuery(document).ready(function($){';
		$js .= '$("#' . $this->id . '").spectrum({';
		$js .= 'showInput: true,';
		$js .= 'showButtons: false,';
		$js .= 'preferredFormat: \'rgb\',';
		$js .= 'allowEmpty: true,';
		$js .= 'color: \'\',';
		$js .= 'showAlpha: true';
		$js .= '});';	
		$js .= '});';
		
		
		$doc->addScriptDeclaration($js);


		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
				
		$output .= '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $onchange . '/>';		
			
			
		return $output;		
		
	}
	
	
	
	
	/**
	 *
	 * Method to get default template style id
	 *
	 */	
	public static function getModuleName()
	{
		
		
		// Core Joomla variables
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$id = JRequest::getVar('id');
		
		$query .= 'SELECT module FROM #__modules WHERE id=' . $id;
		
		$db->setQuery($query);
		$row = $db->loadResult();
		
		return $row;		
		
		
	}
	
	
	
}