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
	
	socket.on('reset_answers', function (response) {
		socket.broadcast.emit('reset_answers',{
			unit_code: response.unit_code,
		});
	});
	
	socket.on('updated_uscale', function (response) {
		socket.broadcast.emit('updated_uscale',{
			unit_code: response.unit_code,
		});
	});
	
	socket.on('reset_uscale', function (response) {
		socket.broadcast.emit('reset_uscale',{
			unit_code: response.unit_code,
		});
	});
   
	socket.on('updated_vote', function (response) {
		socket.broadcast.emit('updated_vote',{
			unit_code: response.unit_code,
			votenum: response.votenum,
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