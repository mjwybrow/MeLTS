<?php
// Written by Nathan Sherburn
// Created 22 July 2013
// For lecturers to grab a snapshot of the students' answers to quiz questions

// Download file

$download_file = 'results.csv';
header( "Content-Type: text/csv;charset=utf-8" );
header( "Content-Disposition: attachment;filename=\"$download_file\"" );
header("Pragma: no-cache");
header("Expires: 0");

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];
$question_number = 1;

// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

// Check whether the username for the unit already existed
$resource = mysql_query("SELECT * FROM lecturer_ques");

// If error in selecting table
if(!$resource) {
	$err=mysql_error();
	print $err;
	exit();
}

if(mysql_affected_rows()==0){ // Do a check for no questions

}
else{
	$csv_container = array();

	$result_res = mysql_query("SELECT * FROM student_list");
	$question_row = array();
	array_push($question_row, 'Lecturer'); // Make 'Lecturer' column title
	array_push($question_row, 'Answers'); // Make 'Answers' column title
	while ($student_list = mysql_fetch_array($result_res)) { // Make all student's name column title's
			$student = ($student_list["username"]);
			array_push($question_row, $student);
	}
	array_push($csv_container, $question_row); // Create top row of table
	
	// Get answers and lecturers
	$answers_res = mysql_query("SELECT ANSWERS FROM lecturer_ques");
	$lecturer_res = mysql_query("SELECT username FROM lecturer_ques");
	
	while ($lecturer_questions = mysql_fetch_array($resource)) { // Obtain all lecturer quiz questions
		$q_number = $lecturer_questions["id"]; // the question number (ie q_2 in database)
		$lec_ques = $lecturer_questions["lec_ques"]; // the worded lecturer question
		$result_res = mysql_query("SELECT * FROM student_list"); // obtaining the resource from the database
		$question_row = array();
		
		
		// Create lecture key
		$lecturer_list = mysql_fetch_array($lecturer_res); // // Get lecturers into a variable
		array_push($question_row, $lecturer_list[0]); // push answers onto the end of the question row
		
		// Create answer key
		$answer_string = '';
		$answer_list = mysql_fetch_array($answers_res); // // Get answers into a variable
		if($answer_list[0][0] == 'A') $answer_string = 'btnA ';
		if($answer_list[0][1] == 'B') $answer_string = $answer_string.'btnB ';
		if($answer_list[0][2] == 'C') $answer_string = $answer_string.'btnC ';
		if($answer_list[0][3] == 'D') $answer_string = $answer_string.'btnD ';
		array_push($question_row, $answer_string); // push answers onto the end of the question row
		
		while ($student_list = mysql_fetch_array($result_res)) { // Obtain all lecturer quiz questions
			$student = ($student_list["username"]);
			$result = mysql_query("SELECT * FROM q_$q_number WHERE username = '$student'");
			$row = mysql_fetch_array($result);
			$answer = $row["mcq_answer"];
			array_push($question_row, $answer);
		}
		
		// put question row at bottom of table
		array_push($csv_container, $question_row);
		$question_number ++;
	}
}

$fp = fopen('php://output', 'w');

foreach ($csv_container as $fields) 
{
	fputcsv($fp, $fields);
}
fclose($fp);
exit();


// Close connection to mySOL
mysql_close($dbcon);
?>