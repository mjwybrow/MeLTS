// Written by Nathan Sherburn
// Created 26 August 2013
// For students to change their alias on the leaderboard

$(document).ready(function() { 

	$(document).on('click','#change_nickname', function(){
		
		// get student's alias
		var nickname = $('#nickname').val();
		if (!nickname) { alert('Please enter an alias.'); return false; }
		nickname = escape(nickname);
		nickname = nickname.replace(/\+/g, "%2B");
			  
		//use jquery ajax to post data to php server
		$.ajax({
			url: "change_alias.php",
			type: 'post',
			data: 'nickname='+nickname,
			success: function (result) {
				//results sent by PHP
				if (result=="1"){
					alert("Alias changed successfully.");
					location.reload();
				}
				else if (result=="0"){
					alert("This alias is already taken.");
				}
				else{
					//print errors sent by PHP
					alert(result);
				}
				
				// Clear all textboxes
				$("#nickname").val('');
			},
			error: function(){	
				alert('There was an error changing alias');	
			}
		});// ajax
		return false;
	});//onclick submit student button
	
	// get current alias and post it to html site
	$.get("get_alias.php", function(data){
		$('#current_alias').html(' ('+data+')');
	});
	
}); //doc ready