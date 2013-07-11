// Written by Shea Yuin Ng
// Created 15 May 2013
// For both lecturers and students to change the settings for their account

$(document).ready(function() {

	$(document).on('click','#change_pass', function(){
		
		//get current password
		var currpswd = $('#currpswd').val();
		if (!currpswd) { alert('Please enter your current password.'); return false; }
		currpswd = escape(currpswd);
		currpswd = currpswd.replace(/\+/g, "%2B");
		
		//get new password
		var newpswd = $('#newpswd').val();
		if (!newpswd) { alert('Please enter your new password.'); return false; }
		newpswd = escape(newpswd);
		newpswd = newpswd.replace(/\+/g, "%2B");
		
		//get retyped new password
		var repswd = $('#repswd').val();
		if (!repswd) { alert('Please retype your new password.'); return false; }
		repswd = escape(repswd);
		repswd = repswd.replace(/\+/g, "%2B");
		
		// Check if current password is equal to the new password
		if (currpswd==newpswd){ alert('New password is similar to current password!'); return false; }
		if (newpswd!=repswd){ alert('New passwords not matching!'); return false; }
		
		//use jquery ajax to post data to php server
		$.ajax({
			url: "change_password.php",
			type: 'post',
			data: 'currpswd='+currpswd+'&newpswd='+newpswd+'&repswd='+repswd,
			success: function (result) {
				//results sent by PHP
				if(result=='1')
					$('#msg').text("Password successfully changed.")
				else
					alert(result);
			},
			error: function(){	
				alert("Please log in.");
				$.mobile.changePage("#page_login", "slideup");
			}
		});// ajax
		return false;
	});// onclick change password button
});//document ready