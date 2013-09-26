// Written by Nathan Sherburn
// Created 26 September 2013
// For lecturers to create and remove teams

$.post("join_session.php", function(data){

	var string = data.split('_');
	var name = string[0];
	var unit_code = string[1];
	var socket = io.connect('http://'+location.host+':8000');

	// at document ready
	$(document).ready(function(){
		
		// Do an initial check for team colour
		$.get("get_teams.php", function(data){
			if(data == 'red'){
				document.getElementById('team_line').innerHTML = '<div class="lineRed"></div>';
			}
			else if(data == 'blue'){
				document.getElementById('team_line').innerHTML = '<div class="lineBlue"></div>';
			}
			else{
				document.getElementById('team_line').innerHTML = '';
			}
		});
				
		// Change team colour bar on update
		socket.on('teams_update', function (data){
			if (unit_code == data.unit_code){
				$.get("get_teams.php", function(data){
					if(data == 'red'){
						document.getElementById('team_line').innerHTML = '<div class="lineRed"></div>';
					}
					else if(data == 'blue'){
						document.getElementById('team_line').innerHTML = '<div class="lineBlue"></div>';
					}
					else{
						document.getElementById('team_line').innerHTML = '';
					}
					location.reload();
				});
			} // if it is the correct unit
		}); //socket on receive teams update
		
	});
	
});