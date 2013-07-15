// Written by Shea Yuin Ng
// Created 16 April 2013
// For students to view and answer questions from lecturers

$.post("join_session.php", function(data){
	
	var string = data.split('_');
	var name = string[0];
	var unit_code = string[1];
	var lec_uname = string[2];
	var socket = io.connect('http://'+location.host+':8000');
	 
	 // at document read (runs only ones).
	 $(document).ready(function(){
		
		// Query the database when student first logged in
		$.ajax({
			url: "stud_query_db.php",
			dataType: "xml",  
			success: function (xml) {
			//results sent by PHP	
			
				// Read xml file
				$(xml).find('Ques').each(function(){ 	
					var lec_ques = $(this).find('Question').text(); 
					var A = $(this).find('A').text();
					var B = $(this).find('B').text(); 	
					var C = $(this).find('C').text(); 	
					var D = $(this).find('D').text(); 	
					var prev_ans = $(this).find('PrevAns').text();
					
					if (lec_ques!= "0"){// means there's question posted by lecturer
						$('#lec_ques').html(lec_ques);
						//$('#btnA').text(data.A); not working
						$('#btnA').parent().find('.ui-btn-text').text('A : '+ A);
						$('#btnB').parent().find('.ui-btn-text').text('B : '+ B);
						$('#btnC').parent().find('.ui-btn-text').text('C : '+ C);
						$('#btnD').parent().find('.ui-btn-text').text('D : '+ D);
						
						if(prev_ans !="0"){ 
							var button = "#"+prev_ans;
							$(button).buttonMarkup({ theme: "k" });
							//$(button).attr('data-theme', 'e'); not working
						}
					}
				});				
			},
			error: function(){	
				alert("Please log in!");
				$.mobile.changePage($(document.location.href="index.html"), "slideup");  
			}	
		});// ajax
		
		// On receiving questions
		socket.on('ques', function (data){
			if (unit_code == data.unit_code && lec_uname == data.lec_name){
				var id = data.id;

				$.ajax({
					url: "update_quesID.php",
					type: 'post',
					data: 'id=' + id,
					success: function (prev_ans) {
						$(".ans_button").buttonMarkup({ theme: "j" });
						if(prev_ans !='0'){ 
							var button = "#" + prev_ans;
							$(button).buttonMarkup({ theme: "k" });
						}
					},
					error: function(){	
						alert('There was an error updating question ID');
					}
				});
				
				// display data
				$('#lec_ques').html(data.ques);
				$('#btnA').parent().find('.ui-btn-text').text(data.A);
				$('#btnB').parent().find('.ui-btn-text').text(data.B);
				$('#btnC').parent().find('.ui-btn-text').text(data.C);
				$('#btnD').parent().find('.ui-btn-text').text(data.D);

			}// if it is the correct unit
			
		});//socket on receive ques
		
		socket.on('reset_answers', function (data){
			if (unit_code == data.unit_code){
				$(".ans_button").buttonMarkup({ theme: "j" });
			}// if it is the correct unit
		});//socket on receive ques
		
		// ask user to log in again if no username available.
		while (name == '') {
		   name = alert("Please log in!");
		   $.mobile.changePage($(document.location.href="index.html"), "slideup");
		}
		
		// send the name to the server, and the server's 
		// register wait will recieve this.
		socket.emit('register', name );
		
		// When a button is clicked / Student answers question
		$(document).on('click','button', function(){
			
			// Get the id of the button clicked
			//var lec_ques = $('#lec_ques').val();
			var mcq_answer = $(this).prop("id");

			$.ajax({
				//url: "http://syngtest.myproject/stu_answers.php",
				url: "stu_answers.php",
				type: 'post',
				data: 'mcqanswer='+ mcq_answer,
				success: function (data) {
				//results sent by PHP
					var result = data.split('_');
					var unit_code = result[0];
					var id = result[1];
					var flag = result[2];
					
					if(flag==1){// Change response
						$(".ans_button").buttonMarkup({ theme: "j" });
						var button = "#" + mcq_answer;
						$(button).buttonMarkup({ theme: "k" });
					}
					else{// Retract response
						$(".ans_button").buttonMarkup({ theme: "j" });
					}
					
					socket.emit('updated',{
						unit_code: unit_code,
						id: id,
						mcq_answer: mcq_answer
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