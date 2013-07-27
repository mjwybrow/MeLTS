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

// Create database for the unit to hold sessions
$database_name = $unit_code.'_'.$uname;
	
// Select database to connect
mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");

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
	// Output data in XML format
	//header("Content-type: text/xml"); //Declare saving data in XML form
	/* echo '<?xml version="1.0" encoding="utf-8"?>'; */
	//echo "<StudentResults>"; //Top root directory
	//echo "<UnitCode>$unit_code</UnitCode>";
	/*$student_list_all = array();
	$resource_student_list = mysql_query("SELECT * FROM student_list");
	while ($all_student_list = mysql_fetch_array($resource_student_list)){ 
		$a_name = ($all_student_list["username"]);
		array_slice($a_name, 0, 20);
		array_push($student_list_all, $a_name);
	
	print_r($student_list_all);
	}*/
	$csv_container = array();
	while ($lecturer_questions = mysql_fetch_array($resource)) { // Obtain all lecturer quiz questions
		// print_r ($lecturer_questions["id"]); for debugging purposes
		$q_number = $lecturer_questions["id"]; // the question number (ie q_2 in database)
		$lec_ques = $lecturer_questions["lec_ques"]; // the worded lecturer question
		$result_res = mysql_query("SELECT * FROM student_list"); // obtaining the resource from the database
		//echo "<Question>"; // beginning the xml
		//echo "<QuestionNum>Qestion $question_number</QuestionNum>";
		$question_row = array();
		while ($student_list = mysql_fetch_array($result_res)) { // Obtain all lecturer quiz questions
			$student = ($student_list["username"]);
			$result = mysql_query("SELECT * FROM q_$q_number WHERE username = '$student'");
			$row = mysql_fetch_array($result);
			$answer = $row["mcq_answer"];
			// Print the XML
			//echo "<Result>";
			//echo "<Student>$student</Student>";
			//echo "<Answer>$answer</Answer>";
			array_push($question_row, $answer);
			//echo "</Result>";
		}
		array_push($csv_container, $question_row);
		//print_r($question_row);
		//echo "</Question>";
		$question_number ++;
	}
	//echo "</StudentResults>";
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