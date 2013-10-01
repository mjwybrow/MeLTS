<?php
// Written by Shea Yuin Ng, Nathan Sherburn
// Created 11 October 2012
// For lecturers to add questions to question list

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get lecturer's username
$uname = $_SESSION['uname'];

//Get question from lecturer
$lec_ques = $_POST['lec_ques'];
$A = $_POST['A'];
$B = $_POST['B'];
$C = $_POST['C'];
$D = $_POST['D'];
$answers = $_POST['ANSWERS'];
//$ip = $_SERVER['REMOTE_ADDR'];

// Enable saving special characters
$lec_ques = mysql_real_escape_string($lec_ques);
$A = mysql_real_escape_string($A);
$B = mysql_real_escape_string($B);
$C = mysql_real_escape_string($C);
$D = mysql_real_escape_string($D);
$answers = mysql_real_escape_string($answers);

// Get username and unit code from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];

mysql_select_db($unit_code, $dbcon) or die("Cannot select database for unit!");

// Insert question into table
mysql_query("INSERT INTO lecturer_ques(username, lec_ques, A, B, C, D, ANSWERS, LOCKED) VALUES('$uname','$lec_ques','$A','$B','$C','$D','$answers', 0)")  or die("Question cannot be added!");

// Get id for question
$get_details="SELECT id FROM lecturer_ques WHERE lec_ques = '$lec_ques'";
// Get ID of the array
$query_details = mysql_query($get_details)  or die("Cannot query details!");
// Get the whole row of information of the question
$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
// Extract 'id' field from the array
$id = $fetch_details['id'];

$table_name='q_'.$id;

// Create a table for each question
mysql_query("CREATE TABLE $table_name (username VARCHAR(20), mcq_answer VARCHAR(4), team VARCHAR(4))") or die("Question table cannot be created!");

// Close connection to mySOL
mysql_close($dbcon);
?>