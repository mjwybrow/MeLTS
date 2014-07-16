// Written by Shea Yuin Ng and Nathan Sherburn
// Created 16 July 2014
// For lecturers to choose which question to edit

$(document).ready(function() {
	$(document).on('click','.chooseques',function(){

		// Get the ID of the question
		var ques_chosen = $(this).attr('data-name');
			
		$.ajax({
			url: "select_ques_edit.php",
			type: 'post',
			data: 'ques_chosen='+ques_chosen,
			success: function (data) {
				if(data==""){
					$.mobile.changePage($(document.location.href="lec_edit_ques.html"), "slideup"); 
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
	});// onclick
});// document ready