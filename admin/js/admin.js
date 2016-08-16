
jQuery(document).ready(function($){
	
	
	
	// Remove ampty space above and below custom section
	$('#square_layout').prev().css({margin:0,padding:0,height:0});
	$('#square_layout').next().css({margin:0,padding:0,height:0});
	$('#default_layout').prev().css({margin:0,padding:0,height:0});
	$('#default_layout').next().css({margin:0,padding:0,height:0});
		
	
	// Show hide 'square' layout options
	hideDivByfield($('#square_layout'),$('#jform_params_modlayout'),'square');
	hideDivByfield($('#default_layout'),$('#jform_params_modlayout'),'default');
	
	
	$('#jform_params_modlayout').on('change', function(){
		
		hideDivByfield($('#square_layout'),$(this),'square');
		hideDivByfield($('#default_layout'),$(this),'default');
		
	});
	


});



function hideDivByfield(div,field,fieldVal)
{
	
	if (field.val() === fieldVal)
	{
		div.show();	
	}
	else
	{
		div.hide();	
	}
		
}