// Written by Shea Yuin Ng
// Created 4 September 2012
// For lecturers to add units taught by them

$(document).ready(function() { 
	$(document).on('click','#add_unit_submit', function(){
		
		//get unit code
		var unit_code = $('#unit_code').val();
		if (!unit_code) { alert('Please enter a unit code.'); return false; }
		if(/^[a-zA-Z0-9- ]*$/.test(unit_code) == false) {
			alert('Please enter a unit code without any special characters'); return false;
		}
		
		//get unit name
		var unit_name = $('#unit_name').val();
		if (!unit_name) { alert('Please enter a unit name.'); return false; }
		unit_name = escape(unit_name);
		unit_name = unit_name.replace(/\+/g, "%2B");
	  
		//use jquery ajax to post data to php server
		$.ajax({
			url: "add_unit.php",
			type: 'post',
			data: 'unit_code='+unit_code+'&unit_name='+unit_name,
			success: function (result) {
			//results sent by PHP
				if (result=="1"){
					$("#message").text("Unit added successfully.");
					
					// Clear the textboxes
					$("#unit_code").val('');
					$("#unit_name").val('');
				}
				else if (result=="0"){
					$("#message").text("Unit existed!!");
					
					// Clear the textboxes
					$("#unit_code").val('');
					$("#unit_name").val('');
				}
				else{
					alert(result);
				}	 
			},
			error: function(){	
				alert('There was an error adding unit');	
			}
		});// ajax
		return false;
	});// onclick add unit
}); //doc ready