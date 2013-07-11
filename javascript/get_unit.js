// Written by Shea Yuin Ng
// Created 19 September 2012
// Get the unit name and code to be printed on the page header
 
 $(document).ready(function(){
	// Use jquery ajax to post data to php server
	$.ajax({
		url: "get_unit.php",
		type: 'post',
		dataType: "xml",  
		success: function (xml) {
			//xml format results sent by PHP
			$(xml).find('Unit').each(function(){  
			var unit_code = $(this).find('UnitCode').text(); 
			var unit_name = $(this).find('UnitName').text();
			
			//Print the results
			document.getElementById("unitcode").innerHTML=unit_code+' '+unit_name;
			});
		},  
		error: function() {  
			alert("Please log in!");
			$.mobile.changePage($(document.location.href="index.html"), "slideup");
		}  
	});// ajax
});//doc ready