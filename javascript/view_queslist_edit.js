// Written by Shea Yuin Ng and Nathan Sherburn
// Created July 16 2014
// For lecturers to view their list of questions

$(document).ready(function() {
	// Use jquery ajax to get data from php server
	$.ajax({
		url: "view_queslist.php",
		type: 'post',
		dataType: "xml",  
		success: function (xml) {
			
			// Read xml file
			$(xml).find('Ques').each(function(){ 
				var id = $(this).find('ID').text(); 
				var ques = $(this).find('Question').text(); 		
				
				if (ques== "0"){// means there's no units registered for the lecturer
					// Empty list, show this msg
					$("#msg").text('Hit "Add Question" to begin');
				}
				else{// list the units in an unordered list
					$("#msg").text('Which question do you want to edit?');	
					$("#lecturer_queslist").append('<li class="chooseques" data-name="'+id+'"><a href="#">'+ques+'</a></li>');
				} 
			})
		},  
		complete:function(){
			$("#lecturer_queslist").listview('refresh');
		},
		error: function() {  
			alert("Please log in!");
			$.mobile.changePage($(document.location.href="index.html"), "slideup");  
		}  
	})// ajax
});