<?php
// Written by Shea Yuin Ng
// Created 22 March 2013
// For lecturers to view their list of questions

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];

// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

// Check whether the username for the unit already existed
// $sql="SELECT id, username, lec_ques FROM lecturer_ques WHERE 1 ORDER BY id DESC";
$sql="SELECT id, lec_ques FROM lecturer_ques WHERE username = '$uname' ORDER BY id DESC";
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
	echo "<QuesList>"; //Top root directory
	echo "<Ques>";
	echo "<ID>0</ID>";
	echo "<Question>0</Question>";
	echo "</Ques>";
	echo "</QuesList>";
}
else{
	// Output data in XML format
	header("Content-type: text/xml"); //Declare saving data in XML form
	echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
	echo "<QuesList>"; //Top root directory
	while($row = mysql_fetch_array($r,MYSQL_ASSOC)){
		//Get each element
		$id = $row["id"];
		$lec_ques = htmlspecialchars($row["lec_ques"]);
		// $lec_name = htmlspecialchars($row["username"]);
		
		// Print each element in XML
		echo "<Ques>";
		echo "<ID>$id</ID>";
		// echo "<LecturersName>$lec_name</LecturersName>";
		echo "<Question>$lec_ques</Question>";
		echo "</Ques>";
	}
	echo "</QuesList>";
}

// Close connection to mySOL
mysql_close($dbcon);
?>