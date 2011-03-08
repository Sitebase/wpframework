// JavaScript Document
jQuery(document).ready(function($) {
	
	/**
	 * Handles toggoling of menus
	 */
	$('.toggle-buttton').bind('click', function() {
		var content = $(this).parent().next();
		var button = $(this);
		if(content.is(':visible')){
			content.animate({
				opacity: 0,
				height: 'toggle'
				}, 500, 'swing');
			button.html("+");	
		}else{
			content.animate({
				opacity: 1,
				height: 'toggle'
				}, 500, 'swing');
			button.html("-");							  
		}
	});
	
});