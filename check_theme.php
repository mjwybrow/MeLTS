<?php
// Written by Nathan Sherburn
// Created 11 August 2013 
// To check which theme the page should display

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get data from session variable
$unit_chosen = $_SESSION['unit_chosen'];
$status = $_SESSION['status'];
if ($status=='S'){
	$lec_uname = $_SESSION['lec_uname'];
	$database_name = $unit_chosen.'_'.$lec_uname;
}
else{
	$uname = $_SESSION['uname'];
	$database_name = $unit_chosen.'_'.$uname;
}

mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");

$css_resource = mysql_query("SELECT * FROM themes WHERE selection=1") or die("Could not find theme!!");
$css_row = mysql_fetch_row($css_resource);
$css_string = $css_row[1];

echo $css_string;

mysql_close($dbcon);
?>