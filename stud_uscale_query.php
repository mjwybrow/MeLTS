<?php
// Written by Shea Yuin Ng
// Created 26 April 2013
// For students to get their previous respond to the understanding scale

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$lec_uname = $_SESSION['lec_uname'];
$unit_code = $_SESSION['unit_chosen'];
$uname = $_SESSION['uname'];

// Create database for the unit to hold sessions
$database_name = $unit_code.'_'.$lec_uname;
	
// Select database to connect
mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");

// Get the details of the unit
$get_details="SELECT * FROM student_list WHERE username = '$uname'";
// Get ID of the array
$query_details = mysql_query($get_details)  or die("Cannot query details!");
// Get the whole row of information of the unit
$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
// Extract 'u_scale' field from the array
$u_scale = $fetch_details['u_scale'];

echo $u_scale;

// Close connection to mySOL
mysql_close($dbcon);
?>