<?php
// Written by Nathan Sherburn
// Created 16 August 2013
// Tally up the results of the student answers

// Connect to mySQL
include('connections.php');

$uname = $_SESSION['uname'];

// Connect to the database
mysql_select_db($unit_chosen, $dbcon) or die("Cannot select unit database!");

// Select all students and their scores
$user_record = mysql_query("SELECT * FROM student_list WHERE username = 'aale32'") or die("Cannot get student");
$user_row = mysql_fetch_array($user_record);

echo "Your score: ";
echo $user_row['score'];
echo "&nbsp Questions attempted: ";
echo $user_row['attempted'];
//echo "</b>"

mysql_close($dbcon);
?>