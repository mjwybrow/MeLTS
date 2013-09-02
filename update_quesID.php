<?php
// Written by Shea Yuin Ng
// Created 27 March 2013
// For students to update the ID of the question posted by the lecturer

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and lecturer's username from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];
$lec_uname = $_SESSION['lec_uname'];

$id = $_POST['id'];
$_SESSION['id'] = $id;

// Check whether the student viewed the question before
$table_name='q_'.$id;

// Create database for the unit to hold sessions
$database_name = $unit_code.'_'.$lec_uname;
	
// Select database to connect
mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");

$reg_stud = mysql_query("SELECT * FROM $table_name WHERE username = '$uname'") or die("Cannot query student's username!");

if(mysql_affected_rows()==0){// If no, insert student's name to the table of answer for the question
	mysql_query("INSERT INTO $table_name(username, mcq_answer) VALUES('$uname','0')")  or die("Cannot insert student into table!");
}
else{// If yes then get previous answer
	// Get the details of the unit
	$get_details="SELECT * FROM $table_name WHERE username = '$uname'";
	// Get ID of the array
	$query_details = mysql_query($get_details)  or die("Cannot query details!");
	// Get the whole row of information of the unit
	$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
	// Extract 'unit_name' field from the array
	$mcq_answer = $fetch_details['mcq_answer'];
	echo $mcq_answer;
}

// Close connection to mySOL
mysql_close($dbcon);
?>