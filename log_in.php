<?php
// Written by Shea Yuin Ng
// Created 23 August 2012
// To login

// Start a new session
session_start();

// Connect to mySQL
include('connections.php');

// Retrieve data from html form through post method
$uname = mysql_real_escape_string($_POST['uname']);
$pswd = mysql_real_escape_string($_POST['pswd']);
$pswd = md5($pswd);

// Select database to connect
mysql_select_db("main_database",$dbcon) or die("Cannot select database!");

// Check the account to log in existed account in the database
$sql="SELECT username FROM account WHERE username = '$uname' and password='$pswd'";
mysql_query($sql)or die("Cannot access table!");

// If username or password is incorrect
if(mysql_affected_rows()==0){
	session_destroy();
	echo "No such login in the system. Please try again.";
	exit();
}
else{// Log in successful, username and password matching
	//Store the variables in the session variable
	$uname = strtolower($uname);
	$_SESSION['uname'] = $uname;
	$_SESSION['pswd'] = $pswd;
	
	// Get the details of login username
	$get_details="SELECT * FROM account WHERE username = '$uname' and password='$pswd'";
	// Get ID of the array
	$query_details = mysql_query($get_details)  or die("Cannot query details!");
	// Get the whole row of information of the user
	$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
	// Extract 'status' and 'first name' field from the array
	$status = $fetch_details['status'];
	$fname = $fetch_details['first_name'];
	// Store status and first name variable in session variable 
	$_SESSION['status'] = $status;
	$_SESSION['fname'] = $fname;

	// Check whether if it's lecturer or student
	if($status=="L"){
		//if lecturer
		echo "L";
	}
	else{
		//if student
		echo "S";
	}
	//print "successfully logged into system.";
	//$ip = $_SERVER['REMOTE_ADDR'];
	//print $ip;
}

// Close connection to mySOL
mysql_close($dbcon);
?>