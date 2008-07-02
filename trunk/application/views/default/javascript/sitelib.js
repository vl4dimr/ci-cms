$(document).ready(function() {
	// show / hide login form			  
	$('#login').hide(); 
	$('a.toggle').click(function() { 
		$('#login').slideToggle(400); 
		$('#breadcrumbs').slideToggle(400); 		
		$("ul#navlogin > li").toggleClass("active");
		return false;
	});
});