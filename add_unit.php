<?php
// Written by Shea Yuin Ng, Nathan Sherburn
// Created 4 September 2012
// For lecturers to add units taught by them

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

//Get unit code and unit name from form
$unit_code = mysql_real_escape_string($_POST['unit_code']);
$unit_name = mysql_real_escape_string($_POST['unit_name']);
$theme_selection = mysql_real_escape_string($_POST['theme_selection']);
//$ip = $_SERVER['REMOTE_ADDR'];

// Select database to connect
mysql_select_db("main_database",$dbcon) or die("Cannot select main database!");

// Get username from session variable
$uname = $_SESSION['uname'];

// Check whether the username already existed
$sql="SELECT * FROM units WHERE unit_code = '$unit_code' and lecturer = '$uname'";
$r = mysql_query($sql);

// If error in selecting table
if(!$r) {
	$err=mysql_error();
	print $err;
	exit();
}

// If username not used before
if(mysql_affected_rows()==0){//no username exist in database
	// Insert username and password into list of units table in database
	mysql_query("INSERT INTO units(unit_code, unit_name, lecturer) VALUES('$unit_code','$unit_name','$uname')")  or die("Unit cannot be added! Please ensure the unit code has only 7 characters including spacing.");
	
	// Create database for the unit to hold sessions
	$database_name = $unit_code.'_'.$uname;
	mysql_query("CREATE DATABASE $database_name");
	
	// Create table in newly created database to store the list of students, a list to store the lecturer questions and a table to store current question
	mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");
<<<<<<< HEAD
	mysql_query("CREATE TABLE student_list (username VARCHAR(20), first_name VARCHAR(30), last_name VARCHAR(50), u_scale VARCHAR(1))")  or die("Students' list table cannot be added!");
=======
	mysql_query("CREATE TABLE student_list (username VARCHAR(20), nickname VARCHAR(20), first_name VARCHAR(30), last_name VARCHAR(50), u_scale VARCHAR(1), score INT(255))")  or die("Students' list table cannot be added!!");
>>>>>>> origin/Leaderboard
	//mysql_query("CREATE TABLE participant (username VARCHAR(10), mcq_answer VARCHAR(4))")  or die("Participants' table cannot be added!!");
	mysql_query("CREATE TABLE lecturer_ques (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY(id),lec_ques VARCHAR(500), A VARCHAR(500), B VARCHAR(500), C VARCHAR(500), D VARCHAR(500), ANSWERS VARCHAR(4), LOCKED INT(1))")  or die("Lecturer's question table cannot be added!!");
	mysql_query("CREATE TABLE current_lecques (id INT, lec_ques VARCHAR(500), A VARCHAR(500), B VARCHAR(500), C VARCHAR(500), D VARCHAR(500))")  or die("Lecturer's current question table cannot be added!!");
	mysql_query("CREATE TABLE students_ques (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY(id), title VARCHAR(500), stu_ques VARCHAR(2500),votes INT)")  or die("Lecturer's current question table cannot be added!!");
	mysql_query("CREATE TABLE themes (selection INT(1), css_string VARCHAR(255))")  or die("Themes table could not be added!!");
	mysql_query("INSERT INTO themes (selection, css_string) VALUES ($theme_selection[0],\"melts.css\")")  or die("Theme value 1 could not be added!!");
	mysql_query("INSERT INTO themes (selection, css_string) VALUES ($theme_selection[1],\"melts_arts.css\")")  or die("Theme value 2 could not be added!!");
	mysql_query("INSERT INTO themes (selection, css_string) VALUES ($theme_selection[2],\"melts_engineering.css\")")  or die("Theme value 3 could not be added!!");
	echo("Unit added");
}
else{
	echo("Failed to add unit");
}

// Close connection to mySOL
mysql_close($dbcon);
?>