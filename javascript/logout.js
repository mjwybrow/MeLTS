// Written by Shea Yuin Ng
// Created 11 September 2012
// To logout

$(document).ready(function() {
	$(document).on('click','#page_logout_submit',function(){
	  
		//use jquery ajax to post data to php server
		$.ajax({
			url: "log_out.php",
			type: 'post',
			success: function (result) {
			//results sent by PHP

				alert(result);
				$.mobile.changePage($(document.location.href="index.html"), "slideup");
				 
			},
			error: function(){	
				alert('There was an error logging out');	
			}
		});// ajax
		return false;
	});// onclick logout
});// document ready