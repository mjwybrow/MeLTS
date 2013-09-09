<?php
// Written by Shea Yuin Ng, Nathan Sherburn
// Created 18 March 2013
// For students to view list  of units after the lecturers added them  

//THIS CAN BE SIMPLIFIED NOW THAT LECTURERS SHARE DATABASES

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Select database to connect
mysql_select_db("main_database", $dbcon) or die("Cannot select database!");

// Get username from session variable
$uname = $_SESSION['uname'];

// Check whether the username for the unit already existed
$r = mysql_query("SELECT unit1, unit2, unit3, unit4, unit5 FROM students WHERE username = '$uname'");

// If error in selecting table
if(!$r) {
	$err=mysql_error();
	print $err;
	exit();
}

if(mysql_affected_rows()==0){// student is not enrolled in any units
	// Output data in XML format
	header("Content-type: text/xml"); //Declare saving data in XML form
	echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
	echo "<Units>"; //Top root directory
	echo "<Unit>";
	echo "<UnitCode>0</UnitCode>";
	echo "<UnitName>0</UnitName>";
	echo "</Unit>";
	echo "</Units>";
}
else{
	// Output data in XML format
	header("Content-type: text/xml"); //Declare saving data in XML form
	echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
	echo "<Units>"; //Top root directory
	
	$unit_codes = mysql_fetch_array($r);
	$unit_code1 = $unit_codes['unit1'];
	$unit_code2 = $unit_codes['unit2'];
	$unit_code3 = $unit_codes['unit3'];
	$unit_code4 = $unit_codes['unit4'];
	$unit_code5 = $unit_codes['unit5'];
	$unit_codes = array($unit_code1, $unit_code2, $unit_code3, $unit_code4, $unit_code5);
	
	foreach($unit_codes as $unit_code){
		$unit_name_resource = mysql_query("SELECT unit_name FROM units WHERE unit_code = '$unit_code'") or die("Cannot select unit name.");
		$unit_name_array = mysql_fetch_array($unit_name_resource);
		if($unit_code != ''){ // Check unitcode is not blank
			// Print each element in XML
			echo "<Unit>";
			echo "<UnitCode>$unit_code</UnitCode>";
			echo "<UnitName>$unit_name_array[0]</UnitName>";
			echo "</Unit>";
		}
	}
	echo "</Units>";
}

// Close connection to mySOL
mysql_close($dbcon);
?>