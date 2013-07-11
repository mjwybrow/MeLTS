// Written by Shea Yuin Ng
// Created 22 March 2013
// For lecturers to add questions to question list

$(document).ready(function() {
	$(document).on('click','#add_ques_submit', function(){
			
	   // get question
		var lec_ques = $("#lec_ques").val();
		if (!lec_ques) { alert('Please enter your question.'); return false;} 
		lec_ques = escape(lec_ques);
		lec_ques =lec_ques.replace(/\+/g, "%2B");

		//get answer A
		var A = $("#A").val();
		//if (!A) { alert('Please enter your answer for A.'); return false; }
		if (!A) { A = ""; }
		A = escape(A);
		A = A.replace(/\+/g, "%2B");

		//get answer B
		var B = $("#B").val();
		//if (!B) { alert('Please enter your answer for B.'); return false; }
		if (!B) { B = ""; }
		B = escape(B);
		B = B.replace(/\+/g, "%2B");

		//get answer C
		var C = $("#C").val();
		//if (!C) { alert('Please enter your answer for C.'); return false; }
		if (!C) { C = ""; }
		C = escape(C);
		C = C.replace(/\+/g, "%2B");

		//get answer D
		var D = $("#D").val();
		//if (!D) { alert('Please enter your answer for D.'); return false; }
		if (!D) { D = ""; }
		D = escape(D);
		D = D.replace(/\+/g, "%2B");
	  
		//use jquery ajax to post data to php server
		$.ajax({
			url: "lec_add_ques.php",
			type: 'post',
			data: 'lec_ques='+lec_ques+'&A='+A+'&B='+B+'&C='+C+'&D='+D,
			success: function (result) {
				if (result==""){
					$("#msg").text("Question added successfully.");
					
					//clear the text spaces
					$("#lec_ques").val('');
					$("#A").val('');
					$("#B").val('');
					$("#C").val('');
					$("#D").val('');
				}
				else{
					$("#msg").text(result);
				}	 
			},
			error: function(){	
				alert('There was an error saving lecturers question');	
			}
		});// ajax
		return false;
	});// onclick add question
});//doc ready