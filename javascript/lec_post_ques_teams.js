// Written by Shea Yuin Ng, Nathan Sherburn
// Created 30 September 2013
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
			url: "lec_post_ques_teams.php",
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
					cntARed = xml.getElementsByTagName("CntARed")[0].childNodes[0].nodeValue;
					cntABlue = xml.getElementsByTagName("CntBlue")[0].childNodes[0].nodeValue;
					cntBRed = xml.getElementsByTagName("CntBRed")[0].childNodes[0].nodeValue;
					cntBBlue = xml.getElementsByTagName("CntBBlue")[0].childNodes[0].nodeValue;
					cntCRed = xml.getElementsByTagName("CntCRed")[0].childNodes[0].nodeValue;
					cntCBlue = xml.getElementsByTagName("CntCBlue")[0].childNodes[0].nodeValue;
					cntDRed = xml.getElementsByTagName("CntDRed")[0].childNodes[0].nodeValue;
					cntDBlue = xml.getElementsByTagName("CntDBlue")[0].childNodes[0].nodeValue;
					totalRed = xml.getElementsByTagName("TotalRed")[0].childNodes[0].nodeValue;
					totalBlue = xml.getElementsByTagName("TotalBlue")[0].childNodes[0].nodeValue;

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
							$( "#barARed" ).progressbar({value: cntARed/totalRed*100});
							$( "#barABlue" ).progressbar({value: cntABlue/totalBlue*100});
							$( "#barBRed" ).progressbar({value: cntBRed/totalRed*100});
							$( "#barBBlue" ).progressbar({value: cntBBlue/totalBlue*100});
							$( "#barCRed" ).progressbar({value: cntCRed/totalRed*100});
							$( "#barCBlue" ).progressbar({value: cntCBlue/totalBlue*100});
							$( "#barDRed" ).progressbar({value: cntDRed/totalRed*100});
							$( "#barDBlue" ).progressbar({value: cntDBlue/totalBlue*100});

							// Check on current status of the lock
							$.get("lock_check.php", function(data){
								locked = data;
								if(locked == 1){
									$('#locked_in').html(' [locked]1');
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

						// update scores
						$('#resultaRed').html(cntARed+'/'+totalRed);
						$('#resultbRed').html(cntBRed+'/'+totalRed);
						$('#resultcRed').html(cntCRed+'/'+totalRed);
						$('#resultdRed').html(cntDRed+'/'+totalRed);
						$('#resultaBlue').html(cntABlue+'/'+totalBlue);
						$('#resultbBlue').html(cntBBlue+'/'+totalBlue);
						$('#resultcBlue').html(cntCBlue+'/'+totalBlue);
						$('#resultdBlue').html(cntDBlue+'/'+totalBlue);
					} 
				})


			},
			error: function(){	
				alert("Please log in!");
				$.mobile.changePage($(document.location.href="index.html"), "slideup");  
			}	
		});
		//ajax

		// Check on current status of the lock (only runs once - on page load)
		$.get("lock_check.php", function(data){
			locked = data;
			if(data == 1){
				$('#locked_in').html(' [locked]');
				$(".resultbarRed > div").css({ 'background': '#D3D3D3' });
				$(".resultbarBlue > div").css({ 'background': '#D3D3D3' });
			}
			else{
				$('#locked_in').html('');
				$(".resultbarRed > div").css({ 'background': '#FF8A84' });
				$(".resultbarBlue > div").css({ 'background': '#6DC5DB' });
			}
		});

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
				$('#locked_in').html(' [locked]');
				$(".resultbarRed > div").css({ 'background': '#D3D3D3' });
				$(".resultbarBlue > div").css({ 'background': '#D3D3D3' });
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
				$(".resultbarRed > div").css({ 'background': '#FF8A84' });
				$(".resultbarBlue > div").css({ 'background': '#6DC5DB' });
			}
			else {
				locked = 1;
				$('#locked_in').html(' [locked]');
				$(".resultbarRed > div").css({ 'background': '#D3D3D3' });
				$(".resultbarBlue > div").css({ 'background': '#D3D3D3' });
			}
			$.post("lock_ques.php"); // switches lock state
			return false;
		});// onclick lock-in answers
		
		// Function that delete all the answers from students
		$(document).on('click',"#reset",function(){
			if(locked != 1){ // check if question is locked
				$.get("reset_result.php", function(data){
					$(function() {
						$( "#barARed" ).progressbar({value: 0});
						$( "#barABlue" ).progressbar({value: 0});
						$( "#barBRed" ).progressbar({value: 0});
						$( "#barBBlue" ).progressbar({value: 0});
						$( "#barCRed" ).progressbar({value: 0});
						$( "#barCBlue" ).progressbar({value: 0});
						$( "#barDRed" ).progressbar({value: 0});
						$( "#barDBlue" ).progressbar({value: 0});
						
					});	

					// Signal answers have been reset
					socket.emit('reset_answers', { 
						unit_code: unit_code,			
					});

					$('#resultaRed').html('0/0');
					$('#resultaBlue').html('0/0');
					$('#resultbRed').html('0/0');
					$('#resultbBlue').html('0/0');
					$('#resultcRed').html('0/0');
					$('#resultcBlue').html('0/0');
					$('#resultdRed').html('0/0');
					$('#resultdBlue').html('0/0');
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
				url: "getstu_answers_teams.php",
				type: 'post',
				dataType: "xml",  
				success: function (xml) {

					// results sent by PHP
					cntARed = xml.getElementsByTagName("CntARed")[0].childNodes[0].nodeValue;
					cntABlue = xml.getElementsByTagName("CntABlue")[0].childNodes[0].nodeValue;
					cntBRed = xml.getElementsByTagName("CntBRed")[0].childNodes[0].nodeValue;
					cntBBlue = xml.getElementsByTagName("CntBBlue")[0].childNodes[0].nodeValue;
					cntCRed = xml.getElementsByTagName("CntCRed")[0].childNodes[0].nodeValue;
					cntCBlue = xml.getElementsByTagName("CntCBlue")[0].childNodes[0].nodeValue;
					cntDRed = xml.getElementsByTagName("CntDRed")[0].childNodes[0].nodeValue;
					cntDBlue = xml.getElementsByTagName("CntDBlue")[0].childNodes[0].nodeValue;
					totalRed = xml.getElementsByTagName("TotalRed")[0].childNodes[0].nodeValue;
					totalBlue = xml.getElementsByTagName("TotalBlue")[0].childNodes[0].nodeValue;
					
					// update percentage bars
					$( "#barARed" ).progressbar({value: cntARed/totalRed*100});
					$( "#barBRed" ).progressbar({value: cntBRed/totalRed*100});
					$( "#barCRed" ).progressbar({value: cntCRed/totalRed*100});
					$( "#barDRed" ).progressbar({value: cntDRed/totalRed*100});
					$( "#barABlue" ).progressbar({value: cntABlue/totalBlue*100});
					$( "#barBBlue" ).progressbar({value: cntBBlue/totalBlue*100});
					$( "#barCBlue" ).progressbar({value: cntCBlue/totalBlue*100});
					$( "#barDBlue" ).progressbar({value: cntDBlue/totalBlue*100});
					
					// update scores
					$('#resultaRed').html(cntARed+'/'+totalRed);
					$('#resultbRed').html(cntBRed+'/'+totalRed);
					$('#resultcRed').html(cntCRed+'/'+totalRed);
					$('#resultdRed').html(cntDRed+'/'+totalRed);
					$('#resultaBlue').html(cntABlue+'/'+totalBlue);
					$('#resultbBlue').html(cntBBlue+'/'+totalBlue);
					$('#resultcBlue').html(cntCBlue+'/'+totalBlue);
					$('#resultdBlue').html(cntDBlue+'/'+totalBlue);
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
