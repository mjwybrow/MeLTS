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

mysql_query("UPDATE student_list SET team='' WHERE 1") or die ("Could not clear teams");

// Close connection to mySOL
mysql_close($dbcon);
?>