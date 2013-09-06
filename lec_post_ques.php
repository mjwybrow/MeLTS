<?php
// Written by Shea Yuin Ng
// Created 26 March 2013
// For lecturers to post questions to students

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
$sql="SELECT * FROM current_lecques";
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
	echo "<A>0</A>";
	echo "<B>0</B>";
	echo "<C>0</C>";
	echo "<D>0</D>";
	echo "<CntA>0</CntA>";
	echo "<CntB>0</CntB>";
	echo "<CntC>0</CntC>";
	echo "<CntD>0</CntD>";
	echo "<Total>0</Total>";
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
	
	//Save id of question
	$_SESSION['id'] = $id;
	
	$table_name='q_'.$id;

	$btnA = 'btnA';
	$result = mysql_query("SELECT * FROM $table_name WHERE mcq_answer = '$btnA'", $dbcon);
	$cntA = mysql_num_rows($result); 
	$btnB = 'btnB';
	$result = mysql_query("SELECT * FROM $table_name WHERE mcq_answer = '$btnB'", $dbcon);
	$cntB = mysql_num_rows($result); 
	$btnC = 'btnC';
	$result = mysql_query("SELECT * FROM $table_name WHERE mcq_answer = '$btnC'", $dbcon);
	$cntC = mysql_num_rows($result); 
	$btnD = 'btnD';
	$result = mysql_query("SELECT * FROM $table_name WHERE mcq_answer = '$btnD'", $dbcon);
	$cntD = mysql_num_rows($result); 

	$total = $cntA+$cntB+$cntC+$cntD;
	
	// Print each element in XML
	echo "<Ques>";
	echo "<UnitCode>$unit_code</UnitCode>";
	echo "<ID>$id</ID>";
	echo "<Question>$lec_ques</Question>";
	echo "<A>$A</A>";
	echo "<B>$B</B>";
	echo "<C>$C</C>";
	echo "<D>$D</D>";
	echo "<CntA>$cntA</CntA>";
	echo "<CntB>$cntB</CntB>";
	echo "<CntC>$cntC</CntC>";
	echo "<CntD>$cntD</CntD>";
	echo "<Total>$total</Total>";
	echo "</Ques>";
	echo "</QuesList>";
}

// Close connection to mySOL
mysql_close($dbcon);
?>