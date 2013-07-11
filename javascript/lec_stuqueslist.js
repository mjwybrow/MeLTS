// Written by Shea Yuin Ng
// Created 3 May 2013
// For lecturers view the list of questions posted by students and to delete questions from students 

$.post("join_session.php", function(data){
	
	var string = data.split('_');
	var name = string[0];
	var unit_code = string[1];
	var socket = io.connect('http://'+location.host+':8000');
	 
	 // at document read (runs only ones).
	 $(document).ready(function(){
		// Use jquery ajax to get data from php server
		$.ajax({
			url: "stu_view_ques.php",
			type: 'post',
			dataType: "xml",  
			success: function (xml) {
				
				var counter = 1;

				// Read xml file
				$(xml).find('Ques').each(function(){ 
					var id = $(this).find('ID').text(); 
					var ques_title = $(this).find('Title').text(); 
					var numOfVotes = $(this).find('VoteNum').text(); 		

					if (ques_title== "0"){// means there's no students
						// Empty table, show this msg
						$("#msg").text('No question found!!');
					}
					else{// list the students in a table format
						$("#msg").text('Questions from Students');	
						$("tbody").append('<tr><td align="center"><input type="checkbox" class="cbox" id="'+id+'"/></td><td>'+counter+'</td><td class="chooseques" data-name="'+id+'"><a href="#">'+ques_title+'</a></td><td>'+numOfVotes+'</td></tr>');
						counter = counter+1;
					}
				})
			},  
			complete:function(){
				$("#stud_queslist").listview('refresh');
			},
			error: function() {  
				alert("Please log in!");
				$.mobile.changePage($(document.location.href="index.html"), "slideup");
			}  
		});// ajax

		// When lecturer chose to view a question
		$(document).on('click','.chooseques',function(){
			var ques_chosen = $(this).attr('data-name');

			$.ajax({
				url: "select_stuques.php",
				type: 'post',
				data: 'ques_chosen='+ques_chosen,
				success: function (data) {
					if(status==""){
						$.mobile.changePage($(document.location.href="lec_stuques.html"), "slideup"); 
					}
					else{
						alert(data); 
					}
				},
				error: function(){	
					alert('There was an error selecting the question');	
				}
			});// ajax
			return false;
		});// onclick choose question

		// Select all functionality
		$(document).on('click',"#selectall",function(){
			  $('.cbox').prop('checked', true);
			  return false;
		});

		// Unselect all funtionality
		$(document).on('click',"#unselectall",function(){
			  $('.cbox').prop('checked', false);
			  return false;
		});

		// Submit questions to be deleted
		$(document).on('click',"#delete_studques",function(){
			// Get the list of checked boxes
			var List = [];
			$(':checkbox:checked').each(function(i){
				qid = $(this).prop('id');
				List[i] = qid;
			});
			
			if(List != ""){
				$.ajax({
					url: "delete_stuques.php",
					type: 'post',
					data: 'list='+List,
					success: function (result) {
						//results sent by PHP
						if (result==""){
							// Signal student question is deleted
							socket.emit('del_stu_ques',{
								unit_code: unit_code,
							});//socket emit
							location.reload(true);
						}
						else
						alert(result);
					},
					error: function(){	
						alert('There was an error deleting questions');	
					}
				});// ajax
			}
			else{
				alert("No questions selected!");
			}
			return false;
		});// onclick delete question button
		
		socket.on('stu_add_ques', function (data) {
			if (unit_code==data.unit_code){
					// Use jquery ajax to get data from php server
				$.ajax({
					url: "stu_view_ques.php",
					type: 'post',
					dataType: "xml",  
					success: function (xml) {
						
						var counter = 1;
						// Clear the table
						$("tbody").html("");
						
						// Read xml file
						$(xml).find('Ques').each(function(){ 
							var id = $(this).find('ID').text(); 
							var ques_title = $(this).find('Title').text(); 
							var numOfVotes = $(this).find('VoteNum').text(); 		

							if (ques_title== "0"){// means there's no students
								// Empty table, show this msg
								$("#msg").text('No question found!!');
							}
							else{// list the students in a table format
								$("#msg").text('Questions from Students');	
								$("tbody").append('<tr><td align="center"><input type="checkbox" class="cbox" id="'+id+'"/></td><td>'+counter+'</td><td class="chooseques" data-name="'+id+'"><a href="#">'+ques_title+'</a></td><td>'+numOfVotes+'</td></tr>');
								counter = counter+1;
							}
						})
					},  
					complete:function(){
						$("#stud_queslist").listview('refresh');
					},
					error: function() {  
						alert("Please log in!");
						$.mobile.changePage($(document.location.href="index.html"), "slideup");  
					}  
				});// ajax
			}// if
		});	// on student add question
		
		// Update list if there are students voted		
		socket.on('updated_vote', function (data) {
			if (unit_code==data.unit_code){
					// Use jquery ajax to get data from php server
				$.ajax({
					url: "stu_view_ques.php",
					type: 'post',
					dataType: "xml",  
					success: function (xml) {
						
						var counter = 1;
						// Clear the table
						$("tbody").html("");
						
						// Read xml file
						$(xml).find('Ques').each(function(){ 
							var id = $(this).find('ID').text(); 
							var ques_title = $(this).find('Title').text(); 
							var numOfVotes = $(this).find('VoteNum').text(); 		

							if (ques_title== "0"){// means there's no students
								// Empty table, show this msg
								$("#msg").text('No question found!!');
							}
							else{// list the students in a table format
								$("#msg").text('Questions from Students');	
								$("tbody").append('<tr><td align="center"><input type="checkbox" class="cbox" id="'+id+'"/></td><td>'+counter+'</td><td class="chooseques" data-name="'+id+'"><a href="#">'+ques_title+'</a></td><td>'+numOfVotes+'</td></tr>');
								counter = counter+1;
							}
						})
					},  
					complete:function(){
						$("#stud_queslist").listview('refresh');
					},
					error: function() {  
						alert("An error occurred while processing XML file.");  
					}  
				});// ajax
			}// if
		});// on updated votes			
	});//document ready
});//post join_session