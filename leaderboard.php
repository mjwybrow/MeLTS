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

// Set all scores to 0
mysql_query("UPDATE student_list SET score=0 WHERE 1") or die("Cannot reset score to 0");

// Tally up the results

$question_ids = mysql_query("SELECT id FROM lecturer_ques");

while($id_array = mysql_fetch_array($question_ids)){
$id = $id_array['id'];
$correct_resource = mysql_query("SELECT ANSWERS FROM lecturer_ques WHERE id='$id'") or die("Cannot get correct answers");
$correct = mysql_fetch_array($correct_resource);

if($correct['ANSWERS'][0] == 'A')
	mysql_query("UPDATE student_list SET score=score+1 WHERE username IN (SELECT username FROM q_$id WHERE mcq_answer = 'btnA')") or die ("Could not update score 1");
if($correct['ANSWERS'][1] == 'B')
	mysql_query("UPDATE student_list SET score=score+1 WHERE username IN (SELECT username FROM q_$id WHERE mcq_answer = 'btnD')") or die ("Could not update score 2");
if($correct['ANSWERS'][2] == 'C')
	mysql_query("UPDATE student_list SET score=score+1 WHERE username IN (SELECT username FROM q_$id WHERE mcq_answer = 'btnD')") or die ("Could not update score 3");
if($correct['ANSWERS'][3] == 'D')
	mysql_query("UPDATE student_list SET score=score+1 WHERE username IN (SELECT username FROM q_$id WHERE mcq_answer = 'btnD')") or die ("Could not update score 4");
}

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