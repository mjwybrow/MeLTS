<?php
// Written by Shea Yuin Ng
// Created 16 May 2013
// For lecturers to get the list of student list available from the same unit under another lecturer

// Resume session from previous session
session_start();

// Get unit chosen from session variable
$unit_chosen = $_SESSION['unit_chosen'];
$uname = $_SESSION['uname'];

// Connect to mySQL
include('connections.php');

// Select database to connect
mysql_select_db("main_database",$dbcon) or die("Cannot select database!");

// Get the unitname for other lecturers
$query = "SELECT * FROM units WHERE unit_code='$unit_chosen' AND NOT lecturer='$uname'";
$get_lecname = mysql_query($query) or die("Cannot get other list!");

// Output data in XML format
header("Content-type: text/xml"); //Declare saving data in XML form
echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
echo "<Units>"; //Top root directory

if(mysql_affected_rows()!=0){
	while ($lecname_array = mysql_fetch_array($get_lecname)) {
		// Get id number of every question
		$unit_code = $lecname_array['unit_code'] ;
		$unit_name = $lecname_array['unit_name'] ;
		$lec_uname = $lecname_array['lecturer'] ;
		$unit_name = htmlspecialchars($unit_name);
		$lec_uname = htmlspecialchars($lec_uname);
		
		// Print each element in XML
		echo "<Unit>";
		echo "<UnitCode>$unit_code</UnitCode>";
		echo "<UnitName>$unit_name</UnitName>";
		echo "<LecName>$lec_uname</LecName>";
		echo "</Unit>";

	}
}
else{
	// Print each element in XML
	echo "<Unit>";
	echo "<UnitCode>0</UnitCode>";
	echo "<UnitName>0</UnitName>";
	echo "<LecName>0</LecName>";
	echo "</Unit>";
}
echo "</Units>";//Close root directory

// Close connection to mySOL
mysql_close($dbcon);
?>