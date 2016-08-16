<?php
/**
 * @package		Mb2 A-Z Content
 * @version		1.1.0
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2016 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

defined('_JEXEC') or die;


// Include the helper functions only once
require_once __DIR__ . '/helper.php';


// Get module hed
ModMb2azcontentHelper::moduleHeading($params, array('modid'=>$module->id));


// Prep for Normal or Dynamic Modes
$mode   = $params->get('mode', 'normal');
$idbase = $params->get('catid');

$cacheid = md5(serialize(array ($idbase, $module->module)));

$cacheparams               = new stdClass;
$cacheparams->cachemode    = 'id';
$cacheparams->class        = 'ModMb2azcontentHelper';
$cacheparams->method       = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams   = $cacheid;

$list = JModuleHelper::moduleCache($module, $params, $cacheparams);

if (!empty($list))
{	
	require JModuleHelper::getLayoutPath('mod_mb2azcontent', 'default');
}