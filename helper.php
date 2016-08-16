<?php
/**
 * @package		Mb2 A-Z Content
 * @version		1.1.1
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2016 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

defined('_JEXEC') or die;

$com_path = JPATH_SITE . '/components/com_content/';
require_once $com_path . 'helpers/route.php';

JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');

/**
 * Helper for mod_articles_category
 *
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @since       1.6
 */
abstract class ModMb2azcontentHelper
{
	/**
	 * Get a list of articles from a specific category
	 *
	 * @param   \Joomla\Registry\Registry  &$params  object holding the models parameters
	 *
	 * @return  mixed
	 *
	 * @since  1.6
	 */
	public static function getList(&$params)
	{
		// Get an instance of the generic articles model
		$articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$app       = JFactory::getApplication();
		$appParams = $app->getParams();
		$articles->setState('params', $appParams);

		// Set the filters based on the module params
		$articles->setState('list.start', 0);
		$articles->setState('list.limit', (int) $params->get('count', 0));
		$articles->setState('filter.published', 1);

		// Access filter
		$access     = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$articles->setState('filter.access', $access);

		// Prep for Normal or Dynamic Modes
		$mode = $params->get('mode', 'normal');

		$catids = $params->get('catid');
		$articles->setState('filter.category_id.include', (bool) $params->get('category_filtering_type', 1));

		// Category filter
		if ($catids)
		{
			if ($params->get('show_child_category_articles', 0) && (int) $params->get('levels', 0) > 0)
			{
				// Get an instance of the generic categories model
				$categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
				$categories->setState('params', $appParams);
				$levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
				$categories->setState('filter.get_children', $levels);
				$categories->setState('filter.published', 1);
				$categories->setState('filter.access', $access);
				$additional_catids = array();

				foreach ($catids as $catid)
				{
					$categories->setState('filter.parentId', $catid);
					$recursive = true;
					$items     = $categories->getItems($recursive);

					if ($items)
					{
						foreach ($items as $category)
						{
							$condition = (($category->level - $categories->getParent()->level) <= $levels);

							if ($condition)
							{
								$additional_catids[] = $category->id;
							}
						}
					}
				}

				$catids = array_unique(array_merge($catids, $additional_catids));
			}

			$articles->setState('filter.category_id', $catids);
		}

		// Ordering
		$ordering = $params->get('article_ordering', 'a.ordering');

		if (trim($ordering) == 'random')
		{
			$articles->setState('list.ordering', JFactory::getDbo()->getQuery(true)->Rand());
		}
		else
		{
			$articles->setState('list.ordering', $params->get('article_ordering', 'a.ordering'));
			$articles->setState('list.direction', $params->get('article_ordering_direction', 'ASC'));
		}

		// New Parameters
		$articles->setState('filter.featured', $params->get('show_front', 'show'));
		$articles->setState('filter.author_id', $params->get('created_by', ""));
		$articles->setState('filter.author_id.include', $params->get('author_filtering_type', 1));
		$articles->setState('filter.author_alias', $params->get('created_by_alias', ""));
		$articles->setState('filter.author_alias.include', $params->get('author_alias_filtering_type', 1));
		$excluded_articles = $params->get('excluded_articles', '');

		if ($excluded_articles)
		{
			$excluded_articles = explode("\r\n", $excluded_articles);
			$articles->setState('filter.article_id', $excluded_articles);

			// Exclude
			$articles->setState('filter.article_id.include', false);
		}

		$date_filtering = $params->get('date_filtering', 'off');

		if ($date_filtering !== 'off')
		{
			$articles->setState('filter.date_filtering', $date_filtering);
			$articles->setState('filter.date_field', $params->get('date_field', 'a.created'));
			$articles->setState('filter.start_date_range', $params->get('start_date_range', '1000-01-01 00:00:00'));
			$articles->setState('filter.end_date_range', $params->get('end_date_range', '9999-12-31 23:59:59'));
			$articles->setState('filter.relative_date', $params->get('relative_date', 30));
		}

		// Filter by language
		$articles->setState('filter.language', $app->getLanguageFilter());

		$items = $articles->getItems();

		// Display options
		$show_date        = $params->get('show_date', 0);
		$show_date_field  = $params->get('show_date_field', 'created');
		$show_date_format = $params->get('show_date_format', 'Y-m-d H:i:s');
		$show_category    = $params->get('show_category', 0);
		$show_hits        = $params->get('show_hits', 0);
		$show_author      = $params->get('show_author', 0);
		$show_introtext   = $params->get('show_introtext', 0);
		$introtext_limit  = $params->get('introtext_limit', 100);

		// Find current Article ID if on an article page
		$option = $app->input->get('option');
		$view   = $app->input->get('view');

		if ($option === 'com_content' && $view === 'article')
		{
			$active_article_id = $app->input->getInt('id');
		}
		else
		{
			$active_article_id = 0;
		}

		// Prepare data for display using display options
		foreach ($items as &$item)
		{
			$item->slug    = $item->id . ':' . $item->alias;
			$item->catslug = $item->catid . ':' . $item->category_alias;

			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
			}
			else
			{
				$menu      = $app->getMenu();
				$menuitems = $menu->getItems('link', 'index.php?option=com_users&view=login');

				if (isset($menuitems[0]))
				{
					$Itemid = $menuitems[0]->id;
				}
				elseif ($app->input->getInt('Itemid') > 0)
				{
					// Use Itemid from requesting page only if there is no existing menu
					$Itemid = $app->input->getInt('Itemid');
				}

				$item->link = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $Itemid);
			}

			// Used for styling the active article
			$item->active      = $item->id == $active_article_id ? 'active' : '';
			$item->displayDate = '';

			if ($show_date)
			{
				$item->displayDate = JHtml::_('date', $item->$show_date_field, $show_date_format);
			}

			if ($item->catid)
			{
				$item->displayCategoryLink  = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catid));
				$item->displayCategoryTitle = $show_category ? '<a href="' . $item->displayCategoryLink . '">' . $item->category_title . '</a>' : '';
			}
			else
			{
				$item->displayCategoryTitle = $show_category ? $item->category_title : '';
			}

			$item->displayHits       = $show_hits ? $item->hits : '';
			$item->displayAuthorName = $show_author ? $item->author : '';

			if ($show_introtext)
			{
				$item->introtext = JHtml::_('content.prepare', $item->introtext, '', 'mod_articles_category.content');
				$item->introtext = self::_cleanIntrotext($item->introtext);
			}

			$item->displayIntrotext = $show_introtext ? self::truncate($item->introtext, $introtext_limit) : '';
			$item->displayReadmore  = $item->alternative_readmore;
			
			
			// Get article images
			$articleImages = json_decode($item->images);
			$item->intro_image = $articleImages->image_intro; 
			$item->fulltext_image = $articleImages->image_fulltext;
			
		}

		return $items;
	}

	
	
	
	
	/**
	 * Strips unnecessary tags from the introtext
	 *
	 * @param   string  $introtext  introtext to sanitize
	 *
	 * @return mixed|string
	 *
	 * @since  1.6
	 */
	public static function _cleanIntrotext($introtext)
	{
		$introtext = str_replace('<p>', ' ', $introtext);
		$introtext = str_replace('</p>', ' ', $introtext);
		$introtext = strip_tags($introtext, '<a><em><strong>');
		$introtext = trim($introtext);

		return $introtext;
	}

	
	
	
	/**
	 * Method to truncate introtext
	 *
	 * The goal is to get the proper length plain text string with as much of
	 * the html intact as possible with all tags properly closed.
	 *
	 * @param   string   $html       The content of the introtext to be truncated
	 * @param   integer  $maxLength  The maximum number of charactes to render
	 *
	 * @return  string  The truncated string
	 *
	 * @since   1.6
	 */
	public static function truncate($html, $maxLength = 0)
	{
		$baseLength = strlen($html);

		// First get the plain text string. This is the rendered text we want to end up with.
		$ptString = JHtml::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = false);

		for ($maxLength; $maxLength < $baseLength;)
		{
			// Now get the string if we allow html.
			$htmlString = JHtml::_('string.truncate', $html, $maxLength, $noSplit = true, $allowHtml = true);

			// Now get the plain text from the html string.
			$htmlStringToPtString = JHtml::_('string.truncate', $htmlString, $maxLength, $noSplit = true, $allowHtml = false);

			// If the new plain text string matches the original plain text string we are done.
			if ($ptString == $htmlStringToPtString)
			{
				return $htmlString;
			}

			// Get the number of html tag characters in the first $maxlength characters
			$diffLength = strlen($ptString) - strlen($htmlStringToPtString);

			// Set new $maxlength that adjusts for the html tags
			$maxLength += $diffLength;

			if ($baseLength <= $maxLength || $diffLength <= 0)
			{
				return $htmlString;
			}
		}

		return $html;
	}

	
	
	
	
	
	/**
	 * 
	 *	Method to ger array with first letter of articles title
	 *	  
	 */
	public static function getFirstTitleChar ($list)
	{
			
		$listCount = count($list);
		$output = '';
				
		for ($i=0; $i<$listCount; $i++)	
		{				
			$comma = $i<($listCount-1) ? ',' : '';						
			$x = $i==0 ? 1 : 0;
			
			$output .= mb_substr($list[$i]->title,$x,1) . $comma;				
		}		
		
		$arr = explode(',',$output);
		
		return array_unique($arr);
		
			
	}
	
	
	
	
	
	
	
	/*
	 *
	 * Method to get module scripts and styles
	 *
	 */
	public static function moduleHeading(&$params, $attribs = array())
	{
				
		
		// Joomla core variables
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();
					
			
		// Get module style
		// Check if use have module css file in template css directory
		if (file_exists( JPATH_THEMES . '/' . $app->getTemplate() . '/css/mb2azcontent.css' ))
		{			
			$doc->addStylesheet(JURI::base(true) . '/templates/' . $app->getTemplate() . '/css/mb2azcontent.css');						
		}
		else
		{
			$doc->addStylesheet(JURI::base(true) . '/media/mb2azcontent/css/mb2azcontent.css');
		}	
		
		
		
		// Get module script
		// Get jquery framework
		JHtml::_('jquery.framework');
			
				
		// Get 'jquery.nav' script
		if ($params->get('modlayout','default') === 'default' && $params->get('scroll',1) == 1 && !ModMb2azcontentHelper::isDeclaration('jquery.scrollTo.min.js'))
		{			
			$doc->addScript(JURI::base(true) . '/media/mb2azcontent/js/jquery.scrollTo.min.js');					
		}
		
		
		if (file_exists(JPATH_THEMES . '/' . $app->getTemplate() . '/js/mb2azcontent.js'))
		{
			$doc->addScript(JURI::base(true) . '/templates/' . $app->getTemplate() . '/js/mb2azcontent.js');
		}
		else
		{
			$doc->addScript(JURI::base(true) . '/media/mb2azcontent/js/mb2azcontent.js');	
		}
	
		
		
		// Inline styles
		$css = '';
		$css .= ModMb2azcontentHelper::colors($params,$attribs);
		$css .= $params->get('customcss','');
		
		$doc->addStyleDeclaration($css);		
		
			
	}
	
	
	
	
	
	/**
	 *
	 * Method to check loading script and styles
	 *
	 */	
	public static function isDeclaration($name)
	{
				
		
		$doc = JFactory::getDocument();				
		$declarationarr = preg_match('@.css@',$name) ? $doc->_styleSheets : $doc->_scripts;
			
		foreach ($declarationarr as $k=>$v)
		{					
			if (preg_match('@' . $name . '@', $k))
			{				
				return true;		
			}			
		}
		
		return false;				
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 *
	 * Method get color style
	 *
	 */	
	public static function colors(&$params, $attribs = array())
	{
				
		
		$css = '';				
		$prefix = '.mb2az-' . $attribs['modid'];
				
		
		if ($params->get('accent_color','') !='' && $params->get('modlayout','default') === 'square')
		{
			
			$css .= $prefix . ' .mb2az-alphabet li a,';
			$css .= $prefix . ' .mb2az-letter-item .mb2az-article-item-content-inner';
			$css .= '{';
			$css .= 'background-color:' . $params->get('accent_color','') . ';';
			$css .= '}';				
			
		}
		
		
		if ($params->get('article_bg_color','') !='' && $params->get('modlayout','default') === 'square')
		{
			
			
			$css .= $prefix . ' .mb2az-article-item-content-inner';
			$css .= '{';
			$css .= 'background-color:' . $params->get('article_bg_color','') . ';';
			$css .= '}';
			
			
							
			
		}	
		
		
		return $css;				
		
	}
	
	
	
	
	/**
	 *
	 * Method to get module data attribute
	 *
	 */
	public static function getModData(&$params, $attribs = array()){
		
		$output = '';
		
		
		// Define filter variable
		$filter = ($params->get('modlayout','default') === 'square' && $params->get('isotope',1) == 1 && $params->get('isotope_filter',1) == 1) ? 1 : 0;
		
		
		$output .= ' data-lmode="' . $params->get('modlayout','default') . '"';
		$output .= ' data-filter="' . $filter . '"';
		$output .= ' data-scroll="' . $params->get('scroll',1) . '"';
		$output .= ' data-scrolls="' . $params->get('scroll_speed', 450) . '"';
		$output .= ' data-scrollot="' . $params->get('scroll_offset_top', 0) . '"';
		
		
			
		
		
		return $output;	
		
		
	}
	
	
	
	
	
	/**
	 *
	 * Method to get module class attribute
	 *
	 */
	public static function getModClass(&$params, $attribs = array()){
		
		$output = '';
		
		
		$output .= 'mb2az';	
		$output .= ' mb2az-' . $attribs['modid'];	
		$output .= ' mb2az-layout-' . $params->get('modlayout','default');
		$output .= $params->get('moduleclass_sfx','') !='' ? ' ' . $params->get('moduleclass_sfx','') : '';	
		
		
		return $output;	
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
}
