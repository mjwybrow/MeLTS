<?php
// Written by Nathan Sherburn
// Created 16 August 2013
// Tally up the results of the student answers

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];
$status = $_SESSION['status'];
$id = $_SESSION['id'];

// Set database name
if ($status=='S'){
	$lec_uname = $_SESSION['lec_uname'];
	$database_name = $unit_chosen.'_'.$lec_uname;
}
else{
	$uname = $_SESSION['uname'];
	$database_name = $unit_chosen.'_'.$uname;
}
	
// Select database to connect
mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");

// Tally up the results

// Echo lock status

?>