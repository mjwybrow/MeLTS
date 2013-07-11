// Written by Shea Yuin Ng
// Created 29 August 2012
// With reference from "Javascript Cookies" available at http://www.w3schools.com/js/js_cookies.asp
// To login

$(document).ready(function() {
	// A function to read the cookie
	function getCookie(c_name){
		var c_value = document.cookie;
		var c_start = c_value.indexOf(" " + c_name + "=");
		if (c_start == -1){
			c_start = c_value.indexOf(c_name + "=");
		}
		if (c_start == -1){
			c_value = null;
		}
		else{
			c_start = c_value.indexOf("=", c_start) + 1;
			var c_end = c_value.indexOf(";", c_start);
			if (c_end == -1){
				c_end = c_value.length;
			}
			c_value = unescape(c_value.substring(c_start,c_end));
		}
		return c_value;
	}
	
	// A function to set the cookie
	function setCookie(c_name,value,exdays){
		var exdate=new Date();
		exdate.setDate(exdate.getDate() + exdays);
		var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
		document.cookie=c_name + "=" + c_value;
	}

	// Executes when window loads
	// Read if there is a cookie first
	var details=getCookie("login");
	
	// If there is a cookie
	if (details!=null && details!=""){
		// Split the string into 2 to get the username and password
		details = details.split(';');
		var username = details[0];
		var pswd = details[1];
		
		// If there is a password stored
		if(pswd!=null && pswd!=""){
			$('#uname').val(username);
			$('#pswd').val(pswd);
		}
		else // no password stored
			$('#uname').val(username);
	}
  
	// To delete cookies (set expiry date to minus the number of days set originally)
	/*var ThreeDays = 1*24*60*60*1000;
	var expDate = new Date();
	expDate.setTime (expDate.getTime() - ThreeDays);
	document.cookie = "login" + "=ImOutOfHere; expires=" + expDate.toGMTString();
	console.log("Cookie");*/
	
	$(document).on('click','#page_login_submit',function(){
		
		//get username
		var uname = $('#uname').val();
		if (!uname) { alert('Please enter your user name.'); return false; }
		if ($("#checkbox-uname").prop('checked') == true) {
			// If checkbox1 is checked, save the username in cookie
			setCookie("login",uname,120);
		}
		uname = escape(uname);
		uname = uname.replace(/\+/g, "%2B");
		
		//get password
		var pswd = $('#pswd').val();
		if (!pswd) { alert('Please enter your password.'); return false; }
		if ($("#checkbox-pswd").prop('checked') == true) {
			// If checkbox2 is checked, both username and password are stored in a cookie
			var login = uname+';'+pswd;
			setCookie("login",login,120);
		}
		pswd = escape(pswd);
		pswd = pswd.replace(/\+/g, "%2B");
	  
		//use jquery ajax to post data to php server
		$.ajax({
			url: "log_in.php",
			type: 'post',
			data: 'uname='+uname+'&pswd='+pswd,
			success: function (result) {
				//results sent by PHP
				if (result=="S"){
					$.mobile.changePage($(document.location.href="student_homepage.html"), "slideup");
				}
				else if (result=="L"){
					$.mobile.changePage($(document.location.href="lecturer_homepage.html"), "slideup");
				}
				else{
					alert(result);
					$.mobile.changePage("#page_login", "slideup");
				}	 
			},
			error: function(){	
				alert('There was an error logging in');	
			}
		});// ajax
		return false;
	});//onclick
});// document ready