// Written by Shea Yuin Ng
// Created 3 April 2013
// For lecturers to view the student list for a unit

$(document).ready(function() {
	// Use jquery ajax to get data from php server
	$.ajax({
		url: "view_studlist.php",
		type: 'post',
		dataType: "xml",  
		success: function (xml) {
			
			var counter = 1;

			// Read xml file
			$(xml).find('Student').each(function(){ 
				var stud_uname = $(this).find('Username').text(); 
				var stud_fname = $(this).find('FirstName').text(); 
				var stud_lname = $(this).find('LastName').text(); 			

				if (stud_uname== "0"){// means there's no students
					// Empty table, show this msg
					$("#msg").text('No student found!!');
				}
				else{// list the students in a table format
					$("tbody").append('<tr><td align="center"><input type="checkbox" class="cbox" id="'+stud_uname+'"/></td><td>'+counter+'</td><td>'+stud_uname+'</td><td>'+stud_fname+'</td><td>'+stud_lname+'</td></tr>');
					counter = counter+1;
				}
			})
		},  
		complete:function(){
			$("#stud_list").listview('refresh');
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

	// Delete students
	$(document).on('click',"#delete_stud",function(){
		// Get the username of each student selected
		var List = [];
		$(':checkbox:checked').each(function(i){
			name = $(this).prop('id');
			name = escape(name);
			name = name.replace(/\+/g, "%2B");
			List[i] = name;
		});
		
		// If there are students selected
		if(List != ""){
			$.ajax({
				url: "delete_stud.php",
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
					alert('There was an error deleting students');	
				}
			});// ajax
		}
		else{
			// If there is no student selected
			alert("No students selected!");
		}
		return false;
	});// onclick delete student button
});// doc ready