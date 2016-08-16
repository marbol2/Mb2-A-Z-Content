<?php
/**
 * @package		Mb2 A-Z Content
 * @version		1.1.0
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2016 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

defined('_JEXEC') or die;

$alphabet = $params->get('alphabet','') !='' ? explode(',', $params->get('alphabet','')) : range('A','Z');
$firstTitleChar = ModMb2azcontentHelper::getFirstTitleChar($list);
$i = 0;

?>
<div id="mb2az-<?php echo $module->id; ?>" class="<?php echo ModMb2azcontentHelper::getModClass($params,array('modid'=>$module->id)); ?>"<?php echo ModMb2azcontentHelper::getModData($params); ?>>	
	<?php require JModuleHelper::getLayoutPath('mod_mb2azcontent', 'normal'); ?>    
</div><!-- //end .mb2az -->