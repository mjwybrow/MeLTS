<?php
// Written by Shea Yuin Ng
// Created 3 April 2013
// For lecturers to view the student list for a unit

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username and chosen unit code from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];
	
// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

// Check whether the username for the unit already existed
$sql="SELECT * FROM student_list ORDER BY last_name ASC";
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
	echo "<StudList>"; //Top root directory
	echo "<Student>";
	echo "<Username>0</Username>";
	echo "<FirstName>0</FirstName>";
	echo "<LastName>0</LastName>";
	echo "</Student>";
	echo "</StudList>";
}
else{
	// Output data in XML format
	header("Content-type: text/xml"); //Declare saving data in XML form
	echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
	echo "<StudList>"; //Top root directory
	while($row = mysql_fetch_array($r,MYSQL_ASSOC)){
		//Get each element
		$stud_uname = htmlspecialchars($row["username"]);
		$stud_fname = htmlspecialchars($row["first_name"]);
		$stud_lname = htmlspecialchars($row["last_name"]);
		
		// Print each element in XML
		echo "<Student>";
		echo "<Username>$stud_uname</Username>";
		echo "<FirstName>$stud_fname</FirstName>";
		echo "<LastName>$stud_lname</LastName>";
		echo "</Student>";
	}
	echo "</StudList>";
}

// Close connection to mySOL
mysql_close($dbcon);
?>