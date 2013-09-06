<?php
// Written by Shea Yuin Ng, Nathan Sherburn
// Created 22 October 2012
// For students to answer questions from lecturers

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');


// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];
$unit_name = $_SESSION['unit_name'];
$lec_uname = $_SESSION['lec_uname'];
$id = $_SESSION['id'];

// Select first database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

// Find out if currently locked or not
$sql_resource = mysql_query("SELECT LOCKED FROM lecturer_ques WHERE id='$id'") or die(mysql_error());;
$locked = mysql_fetch_row($sql_resource);

if ($locked[0] == 0) {

	// Get student's answer
	$mcqanswer = $_POST['mcqanswer'];

	// Select database to connect
	mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

	// Create table of the unit added in main database to insert list of students
	//mysql_query("CREATE TABLE $table_name (lec_ques VARCHAR(30), uscale TINYINT(1))")  or die("Unit table cannot be added!");
	$table_name='q_'.$id;

	// Get the details of the unit
	$get_details="SELECT * FROM $table_name WHERE username = '$uname'";
	// Get ID of the array
	$query_details = mysql_query($get_details)  or die("Cannot query details!");
	// Get the whole row of information of the unit
	$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
	// Extract 'mcq_answer' field from the array
	$prev_mcqanswer = $fetch_details['mcq_answer'];

	if ($prev_mcqanswer==$mcqanswer){// To retract answer
		mysql_query("UPDATE $table_name SET mcq_answer='0' WHERE username='$uname'")  or die("Answer not updated!");
		$flag = 0;
	}
	else{// To answer or to change answer
		mysql_query("UPDATE $table_name SET mcq_answer='$mcqanswer' WHERE username='$uname'")  or die("Answer not updated!");
		$flag = 1;
	}

	// Send info back to JS
	echo $unit_code.'_'.$id.'_'.$flag;

};

// Close connection to mySOL
mysql_close($dbcon);
?>