<?php
// Written by Shea Yuin Ng
// Created 11 October 2012
// For lecturers to save question into database to ask students

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

//Get question from lecturer
$lec_ques = $_POST['lec_ques'];
$A = $_POST['A'];
$B = $_POST['B'];
$C = $_POST['C'];
$D = $_POST['D'];
//$ip = $_SERVER['REMOTE_ADDR'];

// Enable saving special characters
$lec_ques = mysql_real_escape_string($lec_ques);
$A = mysql_real_escape_string($A);
$B = mysql_real_escape_string($B);
$C = mysql_real_escape_string($C);
$D = mysql_real_escape_string($D);

// Get username and unit code from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];

// Select database to connect
$database_name = $unit_code.'_'.$uname;
mysql_select_db("$database_name",$dbcon) or die("Cannot select database for unit!");

// Insert question into table
mysql_query("INSERT INTO lecturer_ques(lec_ques, A, B, C, D) VALUES('$lec_ques','$A','$B','$C','$D')")  or die("Question cannot be added!!");

// Get id for question
$get_details="SELECT id FROM lecturer_ques WHERE lec_ques = '$lec_ques'";
// Get ID of the array
$query_details = mysql_query($get_details)  or die("Cannot query details!!");
// Get the whole row of information of the question
$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!!");
// Extract 'id' field from the array
$id = $fetch_details['id'];

$table_name='q_'.$id;

// Create a table for each question
mysql_query("CREATE TABLE $table_name (username VARCHAR(20), mcq_answer VARCHAR(4))") or die("Question table cannot be created!!");

// Close connection to mySOL
mysql_close($dbcon);
?>