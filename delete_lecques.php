<?php
// Written by Shea Yuin Ng
// Created 6 May 2013 
// To delete students from lecturer's unit

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
	$database_name = $unit_chosen.'_'.$uname;
	mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");
	
	// Delete from question list
	mysql_query("DELETE FROM lecturer_ques WHERE  id='$qid[$i]'") or die("Question cannot be deleted!!");
	
	// Drop table containing the results
	$tablename = 'q_'.$qid[$i];
	mysql_query("DROP TABLE $tablename") or die("Question table cannot be dropped!!");
	
}//for every question
?>