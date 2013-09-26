<?php
// Written by Nathan Sherburn
// Created 26 September 2013
// For lecturers to make teams

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get unitcode from session variable
$unit_code = $_SESSION['unit_chosen'];

// Connect to database and insert into the database
mysql_select_db("$unit_code", $dbcon) or die("Cannot select main database!");

$all_students = mysql_query("SELECT * FROM student_list");
$number_of_students = mysql_num_rows($all_students);
mysql_query("UPDATE student_list SET team='blue' WHERE 1") or die ("Could not update teams to blue");

for ($i = 1; $i <= $number_of_students/2; $i++) {
    $red_student = mt_rand(0,$number_of_students);
	echo $red_student;
	
}

// Close connection to mySOL
mysql_close($dbcon);
?>