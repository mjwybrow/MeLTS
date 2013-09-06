<?php
// Written by Shea Yuin Ng
// Created 2 May 2013
// For both lecturers and students to view the list of questions posted by students

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$status = $_SESSION['status'];
$unit_code = $_SESSION['unit_chosen'];

if($status=='L'){

	$uname = $_SESSION['uname'];

	// Select database to connect
	mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

	// Check whether the username for the unit already existed
	$sql="SELECT * FROM students_ques ORDER BY votes DESC";
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
		echo "<Title>0</Title>";
		echo "<VoteNum>0</VoteNum>";
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
			$lec_ques = htmlspecialchars($row["title"]);
			$votes = $row["votes"];
			
			// Print each element in XML
			echo "<Ques>";
			echo "<ID>$id</ID>";
			echo "<Title>$lec_ques</Title>";
			echo "<VoteNum>$votes</VoteNum>";
			echo "</Ques>";
		}
		echo "</QuesList>";
	}
}

else if($status=='S'){
	$lec_uname = $_SESSION['lec_uname'];

	// Select database to connect
	mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

	// Check whether the username for the unit already existed
	//$sql="SELECT * FROM students_ques ORDER BY votes DESC LIMIT 0,5";//To limit the number of questions viewed
	$sql="SELECT * FROM students_ques ORDER BY votes DESC, id DESC ";
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
		echo "<Title>0</Title>";
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
			$lec_ques = htmlspecialchars($row["title"]);
			
			// Print each element in XML
			echo "<Ques>";
			echo "<ID>$id</ID>";
			echo "<Title>$lec_ques</Title>";
			echo "</Ques>";
		}
		echo "</QuesList>";
	}
}
else echo('Error!');

// Close connection to mySOL
mysql_close($dbcon);
?>