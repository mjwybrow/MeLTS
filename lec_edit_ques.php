<?php
// Written by Shea Yuin Ng, Nathan Sherburn
// Created 11 October 2012
// For lecturers to update questions in question list

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
$id = $_SESSION['id'];

// Get username and unit code from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];

mysql_select_db($unit_code, $dbcon) or die("Cannot select database for unit!");

// Insert question into table
mysql_query("UPDATE lecturer_ques SET lec_ques='$lec_ques', A='$A', B='$B', C='$C', D='$D', ANSWERS='$answers', ts_activity = ts_activity WHERE id='$id'") or die("Unable to edit question!");
// , 
// Close connection to mySOL
mysql_close($dbcon);
?>