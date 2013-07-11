// Written by Shea Yuin Ng
// Created 11 September 2012
// For lecturers to delete unit 

$(document).ready(function() {
	$(document).on('click','#delete_unitsubmit', function(){

		//Pop-up to ask if really want to delete unit
		var answer = confirm ("Are you sure you want to delete this unit?")
		if (answer)
			//use jquery ajax to post data to php server
			$.ajax({
				url: "delete_unit.php",
				type: 'post',
				success: function (result) {
				//results sent by PHP

					alert(result);
					$.mobile.changePage($(document.location.href="view_unitpage.html"), "slideup");
					 
				},
				error: function(){	
					alert('There was an error deleting unit');	
				}
			});
		return false;
	});// onclick delete unit
});//doc ready