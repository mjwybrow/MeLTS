<?php
// Written by Shea Yuin Ng
// Created 3 October 2012
// Get the unit name and code to be printed on the page header

// Resume session from previous session
session_start();

// Get unit chosen from session variable
$unit_chosen = $_SESSION['unit_chosen'];
$uname = $_SESSION['uname'];
$status = $_SESSION['status'];

// Connect to mySQL
include('connections.php');

// Select database to connect
mysql_select_db("main_database", $dbcon) or die("Cannot select database!");

if ($status=='L'){
	// Get the unitname
	$get_unitname="SELECT * FROM units WHERE unit_code='$unit_chosen' and lecturer='$uname'";
}
else{
	$lec_uname = $_SESSION['lec_uname'];
	// Get the unitname
	$get_unitname="SELECT * FROM units WHERE unit_code='$unit_chosen'";
}

// Get ID of the array
$query_unitname = mysql_query($get_unitname)  or die("Cannot query unit name!");
// Get the whole row of information of the user
$fetch_unitname = mysql_fetch_array($query_unitname) or die("Cannot fetch unitname!");
// Extract 'unit_name' field from the array
$unit_name = $fetch_unitname['unit_name'];
// Save unit name in session variable
$_SESSION['unit_name']=$unit_name;
$unit_name = htmlspecialchars($unit_name);
$unit_chosen = htmlspecialchars($unit_chosen);

// Output data in XML format
header("Content-type: text/xml"); //Declare saving data in XML form
echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
echo "<Units>"; //Top root directory

// Print each element in XML
echo "<Unit>";
echo "<UnitCode>$unit_chosen</UnitCode>";
// echo "<UnitName>$unit_name</UnitName>";
echo "</Unit>";

echo "</Units>";//Close root directory

// Close connection to mySOL
mysql_close($dbcon);
?>