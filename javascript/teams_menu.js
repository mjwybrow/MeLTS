// Written by Nathan Sherburn
// Created 26 September 2013
// For lecturers to create and remove teams

$.post("join_session.php", function(data){

	var string = data.split('_');
	var name = string[0];
	var unit_code = string[1];
	var socket = io.connect('http://'+location.host+':8000');

	// at document read (runs only once).
	$(document).ready(function(){
	
		// Make teams and signal teams to students
		$(document).on('click','#make_teams', function(data){
			{$.post("make_teams.php");}
			socket.emit('teams_update', { // Signal to server that teams have been made
				unit_code: unit_code,
			});
		});// on click make teams and signal teams to students

		// Remove teams and signal teams to students
		$(document).on('click','#remove_teams', function(data){
			{$.post("remove_teams.php");}
			socket.emit('teams_update', { // Signal to server that teams have been made
				unit_code: unit_code,
			});
		});// on click remove teams and signal teams to students
		
	});
	
});