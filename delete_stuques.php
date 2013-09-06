<?php
// Written by Shea Yuin Ng
// Created 3 May 2013 
// For lecturers to delete questions from students 

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username from session variable
$uname = $_SESSION['uname'];
$unit_chosen = $_SESSION['unit_chosen'];
$unit_name = $_SESSION['unit_name'];
$list = $_POST['list'];

$qid= explode(',', $list);
$j = count($qid);

for ($i=0; $i<$j; $i++){
	//Access the student list of the unit
	mysql_select_db($unit_chosen, $dbcon) or die("Cannot select unit database!");
	
	// Delete from student question list
	mysql_query("DELETE FROM students_ques WHERE  id='$qid[$i]'") or die("Question cannot be deleted!");
	
	// Drop table containing the results
	$tablename = 'sq_'.$qid[$i];
	mysql_query("DROP TABLE $tablename") or die("Question table cannot be dropped!");
	
}//for every question

// Close connection to mySOL
mysql_close($dbcon);
?>