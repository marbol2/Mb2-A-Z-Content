/**
 * @package		Mb2 A-Z Content
 * @version		1.1.0
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2016 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/



jQuery(document).ready(function($){
	
	
	
	
	$('.mb2az').each(function(){
		
		
		
		var container = $(this).find('.mb2az-articles');
		var alphabetList = $(this).find('.mb2az-alphabet');
		var alphabetListItem = alphabetList.find('> li > a');
		
		
		// Define variables for isotope layout and filter
		var isotopeLayout = $(this).data('lmode') === 'square' ? true : false;
		var isotopeFilter = $(this).data('filter') == 1 ? true : false;
		
		
		
		
		
		
		// Alphabet list navigation	
		var scrollLink = $(this).find('.mb2az-scroll');	
		var scrollPlugin = $(this).data('scroll') == 1 ? true : false;	
		var scrollSpeed = $(this).data('scrolls');	
		var offsetTop = $(this).data('scrollot');;
		var isOver = Math.ceil(offsetTop/$(window).height());
		
		if (isotopeLayout === false && scrollPlugin === true)
		{
			scrollLink.click(function(e) {
				e.preventDefault();
				
				var scrollToDiv = $(this).data('scrollto');
				$('html,body').scrollTo($('#' + scrollToDiv), scrollSpeed, {over:'-'+isOver});					
					
			});
		}
		
			
		
	}); // End each function
	
	
});