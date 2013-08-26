<?php
// Written by Nathan Sherburn
// Created 26 August 2013
// For students to change their alias on the leaderboard

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get nickname
$nickname = mysql_real_escape_string($_POST['nickname']);

// Get username from session variable
$uname = $_SESSION['uname'];
$lecu_name = $_SESSION['lec_uname'];
$unit_code = $_SESSION['unit_chosen'];

// Connect to database and insert into the database
 $database_name = $unit_code.'_'.$lecu_name;
 mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");

// Check whether the username already existed
$sql="SELECT * FROM student_list WHERE nickname = '$nickname'";
$r = mysql_query($sql);

// If error in selecting table
if(!$r) {
	$err=mysql_error();
	print $err;
	exit();
}

// If student not registered
if(mysql_affected_rows()==0){
	mysql_query("UPDATE student_list SET nickname = '$nickname' WHERE username = '$uname'");
	echo("1");
}
else{
	echo("0");
}

// Close connection to mySOL
mysql_close($dbcon);
?>