// Written by Shea Yuin Ng
// Created 6 May 2013
// For lecturers to delete their questions from question list

$(document).ready(function() {
	// Use jquery ajax to get data from php server
	$.ajax({
		url: "view_queslist.php",
		type: 'post',
		dataType: "xml",  
		success: function (xml) {
			
			var counter = 1;
			
			// Read xml file
			$(xml).find('Ques').each(function(){ 
				var id = $(this).find('ID').text(); 
				var ques = $(this).find('Question').text(); 		
				
				if (ques== "0"){// means there's no units registered for the lecturer
					// Empty list, show this msg
					$("#msg").text('No questions found!!');
				}
				else{// list the units in an unordered list
					$("tbody").append('<tr><td align="center"><input type="checkbox" class="cbox" id="'+id+'"/></td><td>'+counter+'</td><td>'+ques+'</td></tr>');
					counter = counter+1;
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
	});// ajax

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

	// Submit delete question
	$(document).on('click',"#delete_lecques",function(){
		var List = [];
		
		// Get the ID of all the checked boxes
		$(':checkbox:checked').each(function(i){
			name = $(this).prop('id');
			name = escape(name);
			name = name.replace(/\+/g, "%2B");
			List[i] = name;
		});
		
		// If there are questions selected
		if(List != ""){
			$.ajax({
				url: "delete_lecques.php",
				type: 'post',
				data: 'list='+List,
				success: function (result) {
					//results sent by PHP
					if (result==""){
						location.reload(true);
					}
					else
					alert(result);
				},
				error: function(){	
					alert('There was an error deleting question');	
				}
			});// ajax
		}
		else{
			// If there is no question selected
			alert("No question selected!");
		}
		return false;
	});// on click delete ques button
});//doc ready