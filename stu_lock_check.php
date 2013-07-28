<?php
// Written by Nathan Sherburn
// Created 28 July 2012
// Check status of lock at Quiz session begin

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$lecname = $_SESSION['lec_uname'];
$unit_code = $_SESSION['unit_chosen'];
$status = $_SESSION['status'];
$id = $_SESSION['id'];

// Create database string for the unit which hold quiz sessions
$database_name = $unit_code.'_'.$lecname;
	
// Select database to connect
mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");

// Find out if currently locked or not
$sql_resource = mysql_query("SELECT LOCKED FROM lecturer_ques WHERE id='$id'") or die(mysql_error());;
$locked_row = mysql_fetch_row($sql_resource);
$locked = $locked_row[0];

// Echo lock status
echo ($locked);

?>