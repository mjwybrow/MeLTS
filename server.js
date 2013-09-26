// Written by Shea Yuin Ng, Nathan Sherburn
// Created 28 October 2012
// The socket io websocket server

var io = require('socket.io').listen(8000);

// open the socket connection
io.sockets.on('connection', function (socket) {
   
   // listen for the chat even. and will recieve
   // data from the sender.
   socket.on('ques', function (data) {
   
      // default value of the name of the sender.
      var sender = 'unregistered';
      
      // get the name of the sender
      socket.get('nickname', function (err, name) {
         console.log('Chat message by ', name);
         console.log('error ', err);
         sender = name;
      });   

      // broadcast data recieved from the sender
      // to others who are connected, but not 
      // from the original sender.
      socket.broadcast.emit('ques', {
        unit_code: data.unit_code,
		id: data.id,
		ques: data.ques,
		lec_name: data.lec_name,
		A: data.A,
		B: data.B,
		C: data.C,
		D: data.D,
        msgr : sender
      });
   });
   
   // Let lecturer know have new results
	socket.on('updated', function (answer) {
		socket.broadcast.emit('updated',{
			unit_code: answer.unit_code,
			id: answer.id,
			mcq_answer: answer.mcq_answer
		});
	});

	// Lecturer reset the results for mcq ques
	socket.on('reset_answers', function (response) {
		socket.broadcast.emit('reset_answers',{
			unit_code: response.unit_code,
		});
	});

	// Lecturer has locked in the results for mcq
	socket.on('lock_answers', function (response) {
		socket.broadcast.emit('lock_answers',{
			unit_code: response.unit_code,
		});
	});
	
	// Lecturer has updated the teams
	socket.on('teams_update', function (response) {
		socket.broadcast.emit('teams_update',{
			unit_code: response.unit_code,
		});
	});
	
	// Lecturer has ended the quiz session
	socket.on('end_quiz_session', function (response) {
		socket.broadcast.emit('end_quiz_session',{
			unit_code: response.unit_code,
		});
	});
	
	// Students respond to u-scale
	socket.on('updated_uscale', function (response) {
		socket.broadcast.emit('updated_uscale',{
			unit_code: response.unit_code,
		});
	});

	// Lecturer reset the u-scale
	socket.on('reset_uscale', function (response) {
		socket.broadcast.emit('reset_uscale',{
			unit_code: response.unit_code,
		});
	});
   
	// Students vote for students' question
	socket.on('updated_vote', function (response) {
		socket.broadcast.emit('updated_vote',{
			unit_code: response.unit_code,
			votenum: response.votenum,
		});
	});

	// Students post questions to lecturers
	socket.on('stu_add_ques', function (response) {
		socket.broadcast.emit('stu_add_ques',{
			unit_code: response.unit_code,
		});
	});

	// Lecturer delete students' question
	socket.on('del_stu_ques', function (response) {
		socket.broadcast.emit('del_stu_ques',{
			unit_code: response.unit_code,
		});
	});

   // listen for user registrations
   // then set the socket nickname to 
   socket.on('register', function (name) {
   
      // make a nickname paramater for this socket
      // and then set its value to the name recieved
      // from the register even above. 
      socket.set('nickname', name, function () {
      
      });
   });

});