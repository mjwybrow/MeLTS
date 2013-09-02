<?php
// Written by Shea Yuin Ng
// Created 26 April 2013
// For lecturers to reset the understanding scale responses from students

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];

// Create database for the unit to hold sessions
$database_name = $unit_code.'_'.$uname;
	
// Select database to connect
mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");

// Reset the whole list to 0
mysql_query("UPDATE student_list SET u_scale='0'")  or die("Answer not updated!");

// Send info back to JS
echo $unit_code;

// Close connection to mySOL
mysql_close($dbcon);
?>