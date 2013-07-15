// Written by Shea Yuin Ng
// Created 18 March 2013
// For students to view list  of units after the lecturers added them  

$(document).ready(function() {
	// Use jquery ajax to get data from php server
	$.ajax({
		url: "studview_unit.php",
		type: 'post',
		dataType: "xml",  
		success: function (xml) {
			
			// Read xml file
			$(xml).find('Unit').each(function(){  
				var unit_code = $(this).find('UnitCode').text(); 
				var unit_name = $(this).find('UnitName').text();
				var lec_name = $(this).find('LecName').text();
				
				if (unit_code== "0"){// means there's no units registered for the lecturer
						// Empty list, show this msg
						$("#view_unitmsg").text('No units found!!');
				}
				else{// list the units in an unordered list
						$("#view_unitmsg").text('Please choose a unit');	
						$("#viewunit_optionlist").append('<li class="chooseunit" data-name="'+unit_code+'_'+lec_name+'"><a href="#">'+unit_code+'  '+unit_name+' ('+lec_name+')</a></li>');
				} 
			})
		},  
		complete:function(){
			$("#viewunit_optionlist").listview('refresh');
		},
		error: function() {  
			alert("Please log in!");
			$.mobile.changePage($(document.location.href="index.html"), "slideup");  
		}  
	});// ajax
});//document ready
