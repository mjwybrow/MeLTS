// Written by Shea Yuin Ng
// Created 20 September 2012
// To register lecturers into the system

$(document).ready(function() {
	$(document).on('click','#create_account_submit', function(){

		//get username
		var uname = $('#uname').val();
		if (!uname) { alert('Please enter your user name.'); return false; }
		if(/^[a-zA-Z0-9- ]*$/.test(uname) == false) {
			alert('Please enter a username without any special characters'); return false;
		}
		uname = escape(uname);
		uname = uname.replace(/\+/g, "%2B");
		
		//get password
		var pswd = $('#pswd').val();
		if (!pswd) { alert('Please enter your password.'); return false; }
		pswd = escape(pswd);
		pswd = pswd.replace(/\+/g, "%2B");
		
		//get first name
		var fname = $('#fname').val();
		if (!fname) { alert('Please enter your first name.'); return false; }
		fname = escape(fname);
		fname = fname.replace(/\+/g, "%2B");
		
		//get last name
		var lname = $('#lname').val();
		if (!lname) { alert('Please enter your last name.'); return false; }
		lname = escape(lname);
		lname = lname.replace(/\+/g, "%2B");
		
		//get email
		var email = $('#email').val();
		if (!email) { alert('Please enter your email address.'); return false; }
		email = escape(email);
		email = email.replace(/\+/g, "%2B");
		
		//get status
		var status = $('#status').val();
		
		//use jquery ajax to post data to php server
		$.ajax({
			url: "create_account.php",
			type: 'post',
			data: 'uname='+uname+'&pswd='+pswd+'&fname='+fname+'&lname='+lname+'&status='+status+'&email='+email,
			success: function (result) {
				//results sent by PHP
				if (result=="1"){
					alert('Account successfully created. You may login now.=)');
					$.mobile.changePage($(document.location.href="index.html"), "slideup");
				}
				else{
					alert(result);
					$.mobile.changePage("#create_accountpage", "slideup");
				}	 
			},
			error: function(){	
				alert('There was an error in creating account');	
			}
		});// ajax
		return false;
	});// onclick create account button
});//document ready