<?php

defined('JPATH_PLATFORM') or die;



class JFormFieldMb2modadminjs extends JFormField
{
		
	protected $type = 'Mb2modadminjs';
	
	
	protected function getInput()
	{
		
		// Basic variables
		$output = '';
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();
		$mod = JFormFieldMb2modadminjs::getModuleName();		
		
			
		// Add admin scripts
		$doc->addScript(JURI::root(true) . '/modules/' . $mod . '/admin/js/admin.js');
					
		
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
