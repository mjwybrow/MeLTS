// Written by Shea Yuin Ng
// Created 30 April 2013
// For students to view the list of questions posted by students

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
		// Use jquery ajax to get data from php server
		$.ajax({
			url: "stu_view_ques.php",
			type: 'post',
			dataType: "xml",  
			success: function (xml) {
				
				// Read xml file
				$(xml).find('Ques').each(function(){ 
					var id = $(this).find('ID').text(); 
					var title = $(this).find('Title').text();
							
					if (id == "0"){// means there's no questions posted by students
						// Empty list, show this msg
						$("#view_quesmsg").text('No questions found');
					}
					else{// list the units in an unordered list
						$("#view_quesmsg").text('Please choose a question');	
						$("#stu_queslist").append('<li class="chooseques" data-name="'+id+'"><a href="#">'+title+'</a></li>');
					} 
				})
			},  
			complete:function(){
				$("#stu_queslist").listview('refresh');
			},
			error: function() {  
				alert("Please log in!");
				$.mobile.changePage($(document.location.href="index.html"), "slideup");
			}  
		}); // ajax

		$(document).on('click','.chooseques',function(){
			var ques_chosen = $(this).attr('data-name');

			$.ajax({
				url: "select_stuques.php",
				type: 'post',
				data: 'ques_chosen='+ques_chosen,
				success: function (data) {
					if(data==""){
						$.mobile.changePage($(document.location.href="stu_stuques.html"), "slideup"); 
					}
					else{
						alert(data); 
					}
				},
				error: function(){	
					alert('There was an error selecting the unit');	
				}
			});// ajax
			return false;
		});// onclick when any question is chosen
		
		// Update list if there are students voted
		socket.on('updated_vote', function (data) {
			if (unit_code==data.unit_code){
				// Use jquery ajax to get data from php server
				$.ajax({
					url: "stu_view_ques.php",
					type: 'post',
					dataType: "xml",  
					success: function (xml) {
						
						// Clear the table
						$("#stu_queslist").html("");
						
						// Read xml file
						$(xml).find('Ques').each(function(){ 
							var id = $(this).find('ID').text(); 
							var title = $(this).find('Title').text();
									
							if (id == "0"){// means there's no questions posted by students
								// Empty list, show this msg
								$("#view_quesmsg").text('No questions found!!');
							}
							else{// list the units in an unordered list
								$("#view_quesmsg").text('Please choose a question');	
								$("#stu_queslist").append('<li class="chooseques" data-name="'+id+'"><a href="#">'+title+'</a></li>');
							} 
						})
					},  
					complete:function(){
						$("#stu_queslist").listview('refresh');
					},
					error: function() {  
						alert("Please log in!");
						$.mobile.changePage($(document.location.href="index.html"), "slideup");
					}  
				});// ajax
			}
		});	//on updated vote
			
		socket.on('del_stu_ques', function (data) {
			if (unit_code==data.unit_code){
				// Use jquery ajax to get data from php server
				$.ajax({
					url: "stu_view_ques.php",
					type: 'post',
					dataType: "xml",  
					success: function (xml) {
						
						// Clear the table
						$("#stu_queslist").html("");
						
						// Read xml file
						$(xml).find('Ques').each(function(){ 
							var id = $(this).find('ID').text(); 
							var title = $(this).find('Title').text();
									
							if (id == "0"){// means there's no questions posted by students
								// Empty list, show this msg
								$("#view_quesmsg").text('No questions found!!');
							}
							else{// list the units in an unordered list
								$("#view_quesmsg").text('Please choose a question');	
								$("#stu_queslist").append('<li class="chooseques" data-name="'+id+'"><a href="#">'+title+'</a></li>');
							} 
						})
					},  
					complete:function(){
						$("#stu_queslist").listview('refresh');
					},
					error: function() {  
						alert("Please log in!");
						$.mobile.changePage($(document.location.href="index.html"), "slideup");
					}  
				});
			}
		});	// on delete questions from student

		socket.on('stu_add_ques', function (data) {
			if (unit_code==data.unit_code){
				// Use jquery ajax to get data from php server
				$.ajax({
					url: "stu_view_ques.php",
					type: 'post',
					dataType: "xml",  
					success: function (xml) {
						
						// Clear the table
						$("#stu_queslist").html("");
						
						// Read xml file
						$(xml).find('Ques').each(function(){ 
							var id = $(this).find('ID').text(); 
							var title = $(this).find('Title').text();
									
							if (id == "0"){// means there's no questions posted by students
								// Empty list, show this msg
								$("#view_quesmsg").text('No questions found!!');
							}
							else{// list the units in an unordered list
								$("#view_quesmsg").text('Please choose a question');	
								$("#stu_queslist").append('<li class="chooseques" data-name="'+id+'"><a href="#">'+title+'</a></li>');
							} 
						})
					},  
					complete:function(){
						$("#stu_queslist").listview('refresh');
					},
					error: function() {  
						alert("Please log in!");
						$.mobile.changePage($(document.location.href="index.html"), "slideup");
					}  
				}); // ajax
			}
		});	// on student add question				
	});//document ready
});//post join_session