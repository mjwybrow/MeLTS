<?php
// Written by Nathan Sherburn
// Created 16 August 2013
// Tally up the results of the student answers

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_chosen = $_SESSION['unit_chosen'];
$status = $_SESSION['status'];

// Connect to the database
mysql_select_db($unit_chosen, $dbcon) or die("Cannot select unit database!");

// Select all students and their scores
$student_resource = mysql_query("SELECT * FROM student_list WHERE 1") or die("Cannot get student list");

// Set all scores to 0
while($students = mysql_fetch_array($student_resource)){
		mysql_query("UPDATE student_list SET score=0 WHERE 1") or die('Cannot update score');
};

// Tally up the results
$correct_answer_res = mysql_query("SELECT * FROM lecturer_ques WHERE 1") or die("Cannot read lecturer questions");

while($correct_answer = mysql_fetch_array($correct_answer_res)){
	$student_resource = mysql_query("SELECT * FROM student_list WHERE 1") or die("Cannot get student list");
	$id = $correct_answer['id'];
	//echo $correct_answer['id'];
	//echo'</br>';
	while($students = mysql_fetch_array($student_resource)){
		$student_answer_res = mysql_query("SELECT * FROM q_$id WHERE 1") or die("Cannot read questions");
		$current_student = $students['username'];
		$student_resource_ch = mysql_query("SELECT * FROM student_list WHERE 1") or die("Cannot get student list");
		while($student_answer = mysql_fetch_array($student_answer_res)){
			/*if($student_answer['username']==$current_student){
				echo $current_student;
				echo '</br>';
				echo $correct_answer['ANSWERS'][0];
				echo '</br>';
				echo $student_answer['mcq_answer'][3];
				echo '</br>';
			}*/
			if($student_answer['username']==$current_student && 
			($student_answer['mcq_answer'][3] == $correct_answer['ANSWERS'][0] || 
			 $student_answer['mcq_answer'][3] == $correct_answer['ANSWERS'][1] || 
			 $student_answer['mcq_answer'][3] == $correct_answer['ANSWERS'][2] || 
			 $student_answer['mcq_answer'][3] == $correct_answer['ANSWERS'][3])){
				mysql_query("UPDATE student_list SET score=score+1 WHERE username='$current_student'") or die('Cannot update score');
				//echo 'updating score';
			};
		};
	};
};


// Print the table
echo "
<table border='0' width='100%'>
<tr>
<th align=\"left\">Nickname</th>
<th align=\"left\">Score</th>
</tr>";

// Select all students and their scores
$score_resource = mysql_query("SELECT * FROM student_list ORDER BY score DESC;") or die("Cannot get student list");

// Fill the table
while($score_row = mysql_fetch_array($score_resource)){
	$username = $score_row['username'];
	mysql_select_db('main_database', $dbcon) or die("Cannot select main database!");
	$nickname_resource = mysql_query("SELECT * FROM account WHERE username = '$username'");	
	$nickname_row = mysql_fetch_array($nickname_resource);
	echo "<tr>";
	echo "<td align=\"left\">" . $nickname_row['nickname'] . "</td>";
	echo "<td align=\"left\">" . $score_row['score'] . "</td>";
	echo "</tr>";
}
echo "</table>";

mysql_close($dbcon);
?>