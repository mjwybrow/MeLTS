<?php
// Written by Shea Yuin Ng
// Created 27 March 2013
// For students to get the question from database if lecturer already posted the question

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$lec_uname = $_SESSION['lec_uname'];
$unit_code = $_SESSION['unit_chosen'];
$uname = $_SESSION['uname'];
	
// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

// Check whether the username for the unit already existed
$r = mysql_query("SELECT * FROM current_lecques") or die("Cannot query current lecture question!");

if(mysql_affected_rows()==0){//no current question exist in database
	// Output data in XML format
	header("Content-type: text/xml"); //Declare saving data in XML form
	echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
	echo "<QuesList>"; //Top root directory
	echo "<Ques>";
	echo "<Question>0</Question>";
	echo "<A>0</A>";
	echo "<B>0</B>";
	echo "<C>0</C>";
	echo "<D>0</D>";
	echo "<PrevAns></PrevAns>";
	echo "</Ques>";
	echo "</QuesList>";
}
else{
	// Output data in XML format
	header("Content-type: text/xml"); //Declare saving data in XML form
	echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
	echo "<QuesList>"; //Top root directory
	$current_ques = mysql_fetch_array($r);
	//Get each element
	$id = $current_ques["id"];
	$lec_ques = htmlspecialchars($current_ques["lec_ques"]);
	$A = htmlspecialchars($current_ques["A"]);
	$B = htmlspecialchars($current_ques["B"]);
	$C = htmlspecialchars($current_ques["C"]);
	$D = htmlspecialchars($current_ques["D"]);
	
	// Update question ID
	$_SESSION['id'] = $id;
	
	// Check whether the student viewed the question before
	$table_name='q_'.$id;
	$reg_stud = mysql_query("SELECT * FROM $table_name WHERE username = '$uname'") or die("Cannot query student's username!");
	
	if(mysql_affected_rows()==0){// If no, insert student's name to the table of answer for the question
		mysql_query("INSERT INTO $table_name(username, mcq_answer) VALUES('$uname','0')")  or die("Cannot insert student into table!");
	}
	else{// If yes then get previous answer
		// Get the details of the unit
		$get_details="SELECT * FROM $table_name WHERE username = '$uname'";
		// Get ID of the array
		$query_details = mysql_query($get_details)  or die("Cannot query details!");
		// Get the whole row of information of the unit
		$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
		// Extract 'unit_name' field from the array
		$mcq_answer = $fetch_details['mcq_answer'];
	}
	
	// Print each element in XML
	echo "<Ques>";
	echo "<Question>$lec_ques</Question>";
	echo "<A>$A</A>";
	echo "<B>$B</B>";
	echo "<C>$C</C>";
	echo "<D>$D</D>";
	echo "<PrevAns>$mcq_answer</PrevAns>";
	echo "</Ques>";
	echo "</QuesList>";
}

// Close connection to mySOL
mysql_close($dbcon);
?>