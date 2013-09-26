<?php
// Written by Nathan Sherburn
// Created 26 September 2013
// For students to check which team they're on

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];

// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

// Get team
$sql_resource = mysql_query("SELECT team FROM student_list WHERE username='$uname'") or die("Could not get team name");;
$team_row = mysql_fetch_row($sql_resource);
$team = $team_row[0];

// Echo team
echo ($team);

mysql_close($dbcon);
?>