<?php
// Written by Shea Yuin Ng
// Created 15 May 2013
// For both lecturers and students to change password

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');
	
// Get username, password, first name, last name, email and status from form
$currpswd = mysql_real_escape_string($_POST['currpswd']);
$currpswd = md5($currpswd);
$newpswd = mysql_real_escape_string($_POST['newpswd']);
$newpswd = md5($newpswd);
$repswd = mysql_real_escape_string($_POST['repswd']);
$repswd = md5($repswd);

// Get username from session variable
$uname = $_SESSION['uname'];

// Select database to connect
mysql_select_db("main_database",$dbcon) or die("Cannot select database!");
	
// Check whether the username already existed
$sql="SELECT username FROM account WHERE username = '$uname'";
$r = mysql_query($sql) or die("No accounts selected");

// If username not used before
if(mysql_affected_rows()!=0){// username exist in database
	// Get the details of the user
	$get_details="SELECT * FROM account WHERE username = '$uname'";
	// Get ID of the array
	$query_details = mysql_query($get_details)  or die("Cannot query details!");
	// Get the whole row of information of the unit
	$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
	// Extract 'unit_name' field from the array
	$prevpswd = $fetch_details['password'];
	
	if($currpswd!=$prevpswd){
		echo('Current password is not correct!');
	}
	else{
		// Change the password in database
		mysql_query("UPDATE account SET password='$newpswd' WHERE username='$uname'")  or die("Password not changed!");
		echo('1');// Represent success
	}
}
else{
	echo('Account not found!');// Represents failure
}

// Close connection to mySOL
mysql_close($dbcon);
?>