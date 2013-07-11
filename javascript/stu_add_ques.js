// Written by Shea Yuin Ng
// Created 2 May 2013
// For students to post questions to lecturers

$.post("join_session.php", function(data){
	
	var string = data.split('_');
	var name = string[0];
	var unit_code = string[1];
	var lec_uname = string[2];
	var socket = io.connect('http://'+location.host+':8000');
	
	// at document read (runs only ones).
	 $(document).ready(function(){
		$(document).on('click','#add_ques_submit', function(){
				
		   // get question title
			var ques_title = $("#ques_title").val();
			if (!ques_title) { alert('Please enter your title for the question.'); return false;} 
			ques_title = escape(ques_title);
			ques_title =ques_title.replace(/\+/g, "%2B");

			//get question
			var question = $("#question").val();
			if (!question) { alert('Please enter your question.'); return false; }
			question = escape(question);
			question = question.replace(/\+/g, "%2B");
		  
			//use jquery ajax to post data to php server
			$.ajax({
				url: "stu_add_ques.php",
				type: 'post',
				data: 'ques_title='+ques_title+'&question='+question,
				success: function (result) {
					if (result==""){
						$("#msg").text("Question submitted successfully.");
						
						socket.emit('stu_add_ques',{
							unit_code: unit_code,
						});//socket emit
						
						//clear the text spaces
						$("#ques_title").val('');
						$("#question").val('');
					}
					else{
						$("#msg").text(result);
					}	 
				},
				error: function(){	
					alert('There was an error submitting your question');	
				}
			});// ajax
			return false;
		});// onclick add question submit
	});//document ready
});//post join_session