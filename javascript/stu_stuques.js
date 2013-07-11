// Written by Shea Yuin Ng
// Created 3 May 2013
// For students to view and vote for the question posted by student

$.post("join_session.php", function(data){
	
	var string = data.split('_');
	var name = string[0];
	var unit_code = string[1];
	
	var socket = io.connect('http://'+location.host+':8000');
	 
	 // at document read (runs only ones).
	 $(document).ready(function(){
		// Use jquery ajax to get data from php server
		$.ajax({
			url: "stu_ques.php",
			type: 'post',
			dataType: "xml",  
			success: function (xml) {
				
				// Read xml file
				$(xml).find('Ques').each(function(){  
					var ques_title = $(this).find('Title').text(); 
					var question = $(this).find('Question').text();
					var votenum = $(this).find('VoteNum').text();
					var flag = $(this).find('Flag').text();
						
					$("#ques_title").append(ques_title);
					$("#question").append(question);
					$("#numOfVotes").append('Total Number of Votes: '+votenum);
					
					// Ensure data-icon is plus
					$('#vote').prop('data-icon', 'plus');
					
					// If student already voted, change the button to an unvote button
					if (flag==1){
						$('#vote').prop('data-icon', 'minus');
						$('#vote .ui-icon').addClass('ui-icon-minus').removeClass('ui-icon-plus');
					}
				});
			},  
			error: function() {  
				alert("Please log in!");
				$.mobile.changePage($(document.location.href="index.html"), "slideup");
			}  
		});// ajax

		socket.on('updated_vote', function (data){
			if (unit_code == data.unit_code){
					$("#numOfVotes").html('Total Number of Votes: '+data.votenum);
			}// if it is the correct unit
		});//socket on receive ques
		
		// ask user to log in again if no username available.
		while (name == '') {
		   name = alert("Please log in!");
		   $.mobile.changePage($(document.location.href="index.html"), "slideup");
		}
		
		$(document).on('click','#vote',function(){
			
			// To detect whether plus or minus vote
			btn_status = $('#vote').prop("data-icon");

			$.ajax({
				url: "vote.php",
				data: 'btn_status='+btn_status,
				type: 'post',
				success: function (vote) {
				console.log(vote);
					var votestring = vote.split(',');
					var votenum = votestring[0];
					var btn_status = votestring[1];
				
					$("#numOfVotes").html('Total Number of Votes: '+votenum);
					socket.emit('updated_vote',{
						unit_code: unit_code,
						votenum: votenum,
					});//socket emit
					
					if(btn_status=="plus"){
						$('#vote').prop('data-icon', 'minus');
						$('#vote .ui-icon').addClass('ui-icon-minus').removeClass('ui-icon-plus');
					}
					else{
						$('#vote').prop('data-icon', 'plus');
						$('#vote .ui-icon').addClass('ui-icon-plus').removeClass('ui-icon-minus');
					}
					//$("#vote").prop("disabled", true);// This doesn't fade the button
					//$('#vote').addClass("ui-disabled");// This does
				},
				error: function(){	
					alert('There was an error selecting the unit');	
				}
			});// ajax
			return false;
		});// onclick vote button
	});//document ready
});//post join_session

