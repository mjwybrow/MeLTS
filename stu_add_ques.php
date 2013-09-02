<?php
// Written by Shea Yuin Ng
// Created 2 May 2013
// For students to post questions to lecturers

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

//Get question from students
$ques_title = $_POST['ques_title'];
$question = $_POST['question'];

// Enable saving special characters
$ques_title = mysql_real_escape_string($ques_title);
$question = mysql_real_escape_string($question);

// Get username and unit code from session variable
$lec_uname = $_SESSION['lec_uname'];
$unit_code = $_SESSION['unit_chosen'];

// Select database to connect
$database_name = $unit_code.'_'.$lec_uname;
mysql_select_db("$database_name",$dbcon) or die("Cannot select database for unit!");

// Insert question into table
mysql_query("INSERT INTO students_ques(title, stu_ques, votes) VALUES('$ques_title','$question','0')")  or die("Question cannot be added!");

// Get id for question
$get_details="SELECT id FROM students_ques WHERE title = '$ques_title' and stu_ques = '$question'";
// Get ID of the array
$query_details = mysql_query($get_details)  or die("Cannot query details!");
// Get the whole row of information of the question
$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
// Extract 'id' field from the array
$id = $fetch_details['id'];

$table_name='sq_'.$id;

// Create a table for each question
mysql_query("CREATE TABLE $table_name (username VARCHAR(20))") or die("Question table cannot be added!");

// Close connection to mySOL
mysql_close($dbcon);
?>