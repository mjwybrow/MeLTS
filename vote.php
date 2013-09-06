<?php
// Written by Shea Yuin Ng
// Created 6 May 2013
// For students to vote up or vote down students' questions

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$lec_uname = $_SESSION['lec_uname'];
$unit_code = $_SESSION['unit_chosen'];
$id = $_SESSION['stu_ques_chosen'];
$btn_status=$_POST['btn_status'];

// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

$table_name='sq_'.$id;


$r = mysql_query("SELECT * FROM students_ques WHERE id = '$id'") or die("Cannot query student's question!");
$stu_ques = mysql_fetch_array($r);
$votes = htmlspecialchars($stu_ques["votes"]);

if ($btn_status=='plus'){
	// To keep track who has voted for this question
	mysql_query("INSERT INTO $table_name(username) VALUES('$uname')")  or die("Vote cannot be added!");
	// Recount votes
	$cntvotes = mysql_query("SELECT * FROM $table_name");
	$votes = mysql_num_rows($cntvotes);
	mysql_query("UPDATE students_ques SET votes='$votes' WHERE id = '$id'")  or die("Votes not updated!");
}
else{
	// To keep track who has voted for this question
	mysql_query("DELETE FROM $table_name WHERE  username='$uname'") or die("Vote cannot be retracted!");
	// Recount votes
	$cntvotes = mysql_query("SELECT * FROM $table_name");
	$votes = mysql_num_rows($cntvotes);
	mysql_query("UPDATE students_ques SET votes='$votes' WHERE id = '$id'")  or die("Votes not updated!");
}

// Send info back to JS
//echo $votes','$btn_status;
echo ($votes.','.$btn_status);
	
// Close connection to mySOL
mysql_close($dbcon);
?>