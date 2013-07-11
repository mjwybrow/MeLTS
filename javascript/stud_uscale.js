// Written by Shea Yuin Ng
// Created 26 April 2013
// For students to respond to the understanding scale

$.post("join_session.php", function(data){
	
	var string = data.split('_');
	var name = string[0];
	var unit_code = string[1];
	
	var socket = io.connect('http://'+location.host+':8000');
	
	// ask user to log in again if no username available.
	while (name == '') {
	   name = alert("Please log in!");
	   $.mobile.changePage($(document.location.href="index.html"), "slideup");
	}
	 
	 // at document read (runs only ones).
	 $(document).ready(function(){
		
		// Get student's previous response for understanding scale 
		$.post("stud_uscale_query.php", function(data){
			if (data == "Y")
				$("#Y").buttonMarkup({ theme: "i" });
			else if (data == "N")
				$("#N").buttonMarkup({ theme: "g" });
		});
		
		// On lecturer reseting the scale
		socket.on('reset_uscale', function (data){
			if (unit_code == data.unit_code){
				//Set the button themes to default
				$("#Y").buttonMarkup({ theme: "h" });
				$("#N").buttonMarkup({ theme: "f" });
			}// if it is the correct unit
		});//socket on receive ques
		
		// send the name to the server, and the server's 
		// register wait will recieve this.
		socket.emit('register', name );
		
		
		// When a button is clicked / Student respond to u-scale
		$(document).on('click','.u_scale', function(){
			
			// Get the id of the button clicked
			var u_scale = $(this).prop("id");

			$.ajax({
				url: "stud_uscale.php",
				type: 'post',
				data: 'uscale='+ u_scale,
				success: function (data) {
				//results sent by PHP
					var result = data.split('_');
					var unit_code = result[0];
					var flag = result[1];
					
					if(flag==1){// Change answer
						//Set the button themes to default
						$("#Y").buttonMarkup({ theme: "h" });
						$("#N").buttonMarkup({ theme: "f" });
						
						// Highlight current response button
						if (u_scale == "Y")
							$("#Y").buttonMarkup({ theme: "i" });
						else if (u_scale == "N")
							$("#N").buttonMarkup({ theme: "g" });
					}
					else{// Unanswer
						//Set the button themes to default
						$("#Y").buttonMarkup({ theme: "h" });
						$("#N").buttonMarkup({ theme: "f" });
					}
					
					// Signal student change response
					socket.emit('updated_uscale',{
						unit_code: unit_code,
					});//socket emit
					
				},
				error: function(){	
					alert('There was an error in student answering question');	
				}
			});// ajax stu_answers

			return false;
		});// answer button clicked
	});//document ready
});//post join_session