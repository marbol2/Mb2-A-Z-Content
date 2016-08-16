<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;



class JFormFieldMb2enditem extends JFormField
{
	
	
	protected $type = 'Mb2enditem';	
	protected $title = '';

	
	
	protected function getInput()
	{
		
		$output = '';
		
		$output .= '</div></div>';		
		
		$output .= '</div>';
		
		$output .= '<div><div>';		
		
		return $output;	
		
		
	}
}
