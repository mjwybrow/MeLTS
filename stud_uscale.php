<?php
// Written by Shea Yuin Ng
// Created 26 April 2013
// For students to respond to the understanding scale

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];
$lec_uname = $_SESSION['lec_uname'];

// Get student's response
$u_scale = $_POST['uscale'];

// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

// Get the details of the unit
$get_details="SELECT * FROM student_list WHERE username = '$uname'";
// Get ID of the array
$query_details = mysql_query($get_details)  or die("Cannot query details!");
// Get the whole row of information of the unit
$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
// Extract 'unit_name' field from the array
$prev_uscale = $fetch_details['u_scale'];

if ($prev_uscale==$u_scale){// To retract response
	mysql_query("UPDATE student_list SET u_scale='0' WHERE username='$uname'")  or die("Response not updated!");
	$flag = 0;
}
else{// To respond or to change response
	mysql_query("UPDATE student_list SET u_scale='$u_scale' WHERE username='$uname'")  or die("Response not updated!");
	$flag = 1;
}

// Send info back to JS
echo $unit_code.'_'.$flag;

// Close connection to mySOL
mysql_close($dbcon);
?>