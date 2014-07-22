<?php
// Written by Shea Yuin Ng and Nathan Sherburn
// Created 16 July 2014
// For lecturers to choose which question to post to students

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username and unit code from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];

//Get question from lecturer
$id = $_POST['ques_chosen'];// holds the id number of the ques

mysql_select_db($unit_code, $dbcon) or die("Cannot select database for unit!");

// Get the question based on the id
$get_details="SELECT * FROM lecturer_ques WHERE id = '$id'";
// Get ID of the array
$query_details = mysql_query($get_details)  or die("Cannot query details!");
// Get the whole row of information of the student
$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
// Extract 'unit2','unit3','unit4' and 'unit5' field from the array
$lec_ques = mysql_real_escape_string($fetch_details['lec_ques']);
$A = mysql_real_escape_string($fetch_details['A']);
$B = mysql_real_escape_string($fetch_details['B']);
$C = mysql_real_escape_string($fetch_details['C']);
$D = mysql_real_escape_string($fetch_details['D']);

//Save question chosen into session variables
$_SESSION['lec_ques'] = $lec_ques;
$_SESSION['A'] = $A;
$_SESSION['B'] = $B;
$_SESSION['C'] = $C;
$_SESSION['D'] = $D;
$_SESSION['edit_id'] = $id;

$query = mysql_query("SELECT * FROM current_lecques") or die("Cannot query details!");
$num_rows = mysql_num_rows($query);
if($num_rows != 0){
	// Make sure there will be only one question at one time 
	mysql_query("TRUNCATE TABLE current_lecques")  or die("Lecturer's question table cannot be deleted!");
}

// Close connection to mySOL
mysql_close($dbcon);
?>