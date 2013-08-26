// Written by Shea Yuin Ng
// Created 19 April 2013
// For lecturers to view the responses of the understanding scale

$.post("join_session.php", function(data){
	
	var string = data.split('_');
	var name = string[0];
	var unit_code = string[1];
	var socket = io.connect('http://'+location.host+':8000');
	 
	 // at document read (runs only ones).
	 $(document).ready(function(){
	 
		// Function that delete all the answers from students
		$(document).on('click',"#reset_uscale",function(){
			// Clear all results in database
			$.get("reset_uscale.php", function(data){
				$(function() {
					// Reset progress bar to 0
					$( "#UScale" ).progressbar({
						value: 0
					});
					
					// Style the bar graph and set counters to 0
					$("#UScale").css({ 'background': '#FFD177' });
					$('#uresult1').html('0');
					$('#uresult2').html('0');
				});	

				// Give signal to reset student's buttons
				socket.emit('reset_uscale', { 
					unit_code: data,
				});
			});//get
			return false;
		});//onclick
		
		// Query database for previous result
		$.ajax({
			url: "getu_scale.php",
			type: 'post',
			dataType: "xml",  
			success: function (xml) {

				//results sent by PHP
				cntY = xml.getElementsByTagName("CntY")[0].childNodes[0].nodeValue;
				cntN = xml.getElementsByTagName("CntN")[0].childNodes[0].nodeValue;
				total = xml.getElementsByTagName("Total")[0].childNodes[0].nodeValue;
				
				if (total==0)
				{
					$(function() {
						$( "#UScale" ).progressbar({
							value: 0
						});
					});	
					
					// Style the bar graph
					$("#UScale").css({ 'background': '#FFD177' });
					
					$('#uresult').html('');
					$('#uresult1').html('0');
					$('#uresult2').html('0');
				}
				else{
					$(function() {
						$( "#UScale" ).progressbar({
							value: cntY/total*100
						});
					});	

					// Style the bar graph
					$("#UScale").css({ 'background': '#FF8A84' });
					$("#UScale > div").css({ 'background': '#70E0AD' });
							
					$('#uresult').html(cntY + 'and ' + cntN);
					$('#uresult1').html(cntY);
					$('#uresult2').html(cntN);
				}
			},
			error: function(){	
				alert("Please log in!");
				$.mobile.changePage($(document.location.href="index.html"), "slideup");  
			}
		});	
		
		// When there is student responding to the uscale
		socket.on('updated_uscale', function (data) {
			if (unit_code==data.unit_code){
				$.ajax({
					url: "getu_scale.php",
					type: 'post',
					dataType: "xml",  
					success: function (xml) {

						//results sent by PHP
						cntY = xml.getElementsByTagName("CntY")[0].childNodes[0].nodeValue;
						cntN = xml.getElementsByTagName("CntN")[0].childNodes[0].nodeValue;
						total = xml.getElementsByTagName("Total")[0].childNodes[0].nodeValue;
						
						if (total==0)
						{
							$(function() {
								$( "#UScale" ).progressbar({
									value: 0
								});
							});	
							
							// Style the bar graph
							$("#UScale").css({ 'background': '#FFD177' });
							
							$('#uresult').html('');
							$('#uresult1').html('0');
							$('#uresult2').html('0');
						}
						else{
							$(function() {
								$( "#UScale" ).progressbar({
									value: cntY/total*100
								});
							});	

							// Style the bar graph
							$("#UScale").css({ 'background': '#FF8A84' });
							$("#UScale > div").css({ 'background': '#70E0AD' });
							
							$('#uresult').html(cntY + 'and ' + cntN);
							$('#uresult1').html(cntY);
							$('#uresult2').html(cntN);
						}
					},
					error: function(){	
						alert("Please log in!");
						$.mobile.changePage($(document.location.href="index.html"), "slideup");  
					}
				});// ajax
			}// execute only if the correct unit
		});	// socket on student response		
	});//document ready
});//post join_session