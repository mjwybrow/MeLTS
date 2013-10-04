// Written by Shea Yuin Ng, Nathan Sherburn
// Created 19 April 2013
// For lecturers to post questions and view the results of the posted question

$.post("join_session.php", function(data){

	var string = data.split('_');
	var name = string[0];
	var unit_code = string[1];
	var socket = io.connect('http://'+location.host+':8000');
	var locked;
	
	// at document read (runs only once).
	$(document).ready(function(){

		//use jquery ajax to post data to php server
		$.ajax({
			url: "lec_post_ques.php",
			type: 'post',
			dataType: "xml",
			success: function (xml) {

				// Read xml file
				$(xml).find('Ques').each(function(){ 
					var unit_code = $(this).find('UnitCode').text(); 	
					var id = $(this).find('ID').text(); 	
					var lec_ques = $(this).find('Question').text(); 
					var A = $(this).find('A').text();
					var B = $(this).find('B').text(); 	
					var C = $(this).find('C').text(); 	
					var D = $(this).find('D').text(); 
					cntA = xml.getElementsByTagName("CntA")[0].childNodes[0].nodeValue;
					cntB = xml.getElementsByTagName("CntB")[0].childNodes[0].nodeValue;
					cntC = xml.getElementsByTagName("CntC")[0].childNodes[0].nodeValue;
					cntD = xml.getElementsByTagName("CntD")[0].childNodes[0].nodeValue;
					total = xml.getElementsByTagName("Total")[0].childNodes[0].nodeValue;

					if (lec_ques== "0"){// means there's no question posted by lecturer
						// Empty list, show this msg
						$("p#log").text('No question posted!');
					}
					else{// list the units in an unordered list
						// send message on inputbox to server
						socket.emit('ques', { 
							unit_code: unit_code,
							id: id,
							ques: lec_ques,
							lec_name: name,
							A: A,
							B: B,
							C: C,
							D: D,

						});// socket emit

						$(function() {
							$( "#barA" ).progressbar({
								value: cntA/total*100
							});

							$( "#barB" ).progressbar({
								value: cntB/total*100
							});

							$( "#barC" ).progressbar({
								value: cntC/total*100
							});

							$( "#barD" ).progressbar({
								value: cntD/total*100
							});

							// Check on current status of the lock
							$.get("lock_check.php", function(data){
								locked = data;
								if(locked == 1){
									$('#locked_in').html(' [locked]');
									$(".resultbar > div").css({ 'background': '#D3D3D3' });
								}
								else if(locked == 0){
									$('#locked_in').html('');
									$(".resultbar > div").css({ 'background': '#FFE166' });
								}
								else{
									alert('Something has gone horribly wrong!');
								}
							});
						});	

						$('#lec_ques').html(lec_ques);
						$('#A').html(A);
						$('#B').html(B);
						$('#C').html(C);
						$('#D').html(D);

						$('#resulta').html(cntA+'/'+total);
						$('#resultb').html(cntB+'/'+total);
						$('#resultc').html(cntC+'/'+total);
						$('#resultd').html(cntD+'/'+total);
					} 
				})


			},
			error: function(){	
				alert("Please log in!");
				$.mobile.changePage($(document.location.href="index.html"), "slideup");  
			}	
		});
		//ajax

		// End student side quiz session when lecturer ends quiz session
		$(document).on('click',"#end_ques",function(){
		
			// Signal to server session has ended
			socket.emit('end_quiz_session', { 
				unit_code: unit_code,
			});
			
			$.get("end_session.php", function(data){
				window.location.href = "lec_ques_list.html";
			});
			
			// lock quiz
			if (locked == 0) {
				locked = 1;
				socket.emit('lock_answers', { // Signal to server answers have been locked
					unit_code: unit_code,
				});
				$.post("lock_ques.php");
			}
			
			// update leaderboard
			$.get("update_leaderboard.php");
			
			return false;
		});// onclick end session

		// Function that locks-in all the answers from students
		$(document).on('click',"#lock_in", function(){
			// Signal to server answers have been locked/unlocked
			socket.emit('lock_answers', { 
				unit_code: unit_code,
			});
			if (locked == 1) {
				locked = 0;
				$('#locked_in').html('');
				$(".resultbar > div").css({ 'background': '#FFE166' });
			}
			else if(locked == 0) {
				locked = 1;
				$('#locked_in').html(' [locked]');
				$(".resultbar > div").css({ 'background': '#D3D3D3' });
			}
			else{
				alert('Something has gone horribly wrong, alert the farmers!');
			}
			$.post("lock_ques.php"); // switches lock state
			return false;
		});// onclick lock-in answers
		
		// Function that delete all the answers from students
		$(document).on('click',"#reset",function(){
			if(locked != 1){ // check if question is locked
				$.get("reset_result.php", function(data){
					$(function() {
						$( "#barA" ).progressbar({
							value: 0
						});

						$( "#barB" ).progressbar({
							value: 0
						});

						$( "#barC" ).progressbar({
							value: 0
						});

						$( "#barD" ).progressbar({
							value: 0
						});
						
					});	

					// Signal answers have been reset
					socket.emit('reset_answers', { 
						unit_code: unit_code,			
					});

					$('#resulta').html('0/0');
					$('#resultb').html('0/0');
					$('#resultc').html('0/0');
					$('#resultd').html('0/0');
				});// get
			}; // check if locked
			return false;
		});
		// onclick reset answers

		// Updated answers from students
		socket.on('updated', function (data) {
			var unit_code = data.unit_code;
			var id = data.id;
			var mcq_answer = data.mcq_answer;
			
			$.ajax({
				url: "getstu_answers.php",
				type: 'post',
				dataType: "xml",  
				success: function (xml) {

					//results sent by PHP
					cntA = xml.getElementsByTagName("CntA")[0].childNodes[0].nodeValue;
					cntB = xml.getElementsByTagName("CntB")[0].childNodes[0].nodeValue;
					cntC = xml.getElementsByTagName("CntC")[0].childNodes[0].nodeValue;
					cntD = xml.getElementsByTagName("CntD")[0].childNodes[0].nodeValue;
					total = xml.getElementsByTagName("Total")[0].childNodes[0].nodeValue;
					//$(xml).find('Answer').each(function(){  
					//var cntA = $(this).find('CntA').text(); 
					//var cntB = $(this).find('CntB').text(); 
					//var cntC = $(this).find('CntC').text(); 
					//var cntD = $(this).find('CntD').text(); 
					//var total = $(this).find('Total').text(); 

					//});
					// Display data
					$(function() {
						$( "#barA" ).progressbar({
							value: cntA/total*100
						});

						$( "#barB" ).progressbar({
							value: cntB/total*100
						});

						$( "#barC" ).progressbar({
							value: cntC/total*100
						});

						$( "#barD" ).progressbar({
							value: cntD/total*100
						});

					});					

					$('#resulta').html(cntA+'/'+total);
					$('#resultb').html(cntB+'/'+total);
					$('#resultc').html(cntC+'/'+total);
					$('#resultd').html(cntD+'/'+total);
				},
				error: function(){	
					alert('There was an error in student answering question');	
				}
			});// ajax	
		});
		
		// ask user to log in again if no username available.
		while (name == '') {
		   name = alert("Please log in!");
		   window.location.href = "index.html";
		};

		// send the name to the server, and the server's 
		// register wait will recieve this.
		socket.emit('register', name );
	}); // document ready
});// post
