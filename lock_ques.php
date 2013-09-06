<?php
// Written by Nathan Sherburn
// Created 17th July 2013
// For lecturers to lock in the answers from students

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];
$unit_name = $_SESSION['unit_name'];
$id = $_SESSION['id'];
	
// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

// Find out if currently locked or not
$sql_resource = mysql_query("SELECT LOCKED FROM lecturer_ques WHERE id='$id'") or die(mysql_error());;
$locked = mysql_fetch_row($sql_resource);

// Unlock students' answers
if ($locked[0])
	$locked[0] = 0;
else
	$locked[0] = 1;

//update database
mysql_query("UPDATE lecturer_ques SET LOCKED=$locked[0] WHERE id='$id';") or die("Database did not update!");

// Close connection to mySOL
mysql_close($dbcon);
?>