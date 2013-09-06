<?php
// Written by Shea Yuin Ng
// Created 4 September 2012
// For lecturers to view the list of units added by them

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Select database to connect
mysql_select_db("main_database", $dbcon) or die("Cannot select database!");
 
// Get username from session variable
$uname = $_SESSION['uname'];

// Check whether the username for the unit already existed
$sql="SELECT unit_code, unit_name FROM units WHERE lecturer = '$uname'";
$r = mysql_query($sql);

// If error in selecting table
if(!$r) {
	$err=mysql_error();
	print $err;
	exit();
}

if(mysql_affected_rows()==0){//no units exist in database
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
	while($row = mysql_fetch_array($r,MYSQL_ASSOC)){
		//Get each element
		$unit_code = htmlspecialchars($row["unit_code"]);
		$unit_name = htmlspecialchars($row["unit_name"]);
		
		// Print each element in XML
		echo "<Unit>";
		echo "<UnitCode>$unit_code</UnitCode>";
		echo "<UnitName>$unit_name</UnitName>";
		echo "</Unit>";
	}
	echo "</Units>";
}

// Close connection to mySOL
mysql_close($dbcon);
?>