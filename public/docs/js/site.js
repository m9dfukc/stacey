$(document).ready(function()
{
	$('ul.project-list').each(function() {
	  if ($(this).find('li.thumb').length%2) $(this).append("<li class='col four'></li>"); 
	})
	
	$('.collect a').not(':last').after(", "); 
});