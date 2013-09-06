<?php
// Written by Shea Yuin Ng
// Created 3 May 2013
// For both lecturers and students to view a  question posted by student

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get status
$status = $_SESSION['status'];
$unit_code = $_SESSION['unit_chosen'];
$id = $_SESSION['stu_ques_chosen'];
$stu_uname = $_SESSION['uname'];

// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

// To keep track who has voted for this question
$table_name='sq_'.$id;
$query = mysql_query("SELECT * FROM $table_name WHERE username = '$stu_uname'") or die("Cannot query student's question!");

if(mysql_affected_rows()==0){//not yet vote
	$flag = 0;
}
else{
	$flag = 1;
}

// Check whether the username for the unit already existed
$r = mysql_query("SELECT * FROM students_ques WHERE id = '$id'") or die("Cannot query student's question!");
$stu_ques = mysql_fetch_array($r);
//Get each element
$ques_title = htmlspecialchars($stu_ques["title"]);
$question = htmlspecialchars($stu_ques["stu_ques"]);
$votes = htmlspecialchars($stu_ques["votes"]);

// Output data in XML format
header("Content-type: text/xml"); //Declare saving data in XML form
echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
echo "<QuesList>"; //Top root directory

// Print each element in XML
echo "<Ques>";
echo "<Title>$ques_title</Title>";
echo "<Question>$question</Question>";
echo "<VoteNum>$votes</VoteNum>";
echo "<Flag>$flag</Flag>";
echo "</Ques>";
echo "</QuesList>";

// Close connection to mySOL
mysql_close($dbcon);
?>