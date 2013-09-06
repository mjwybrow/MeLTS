<?php
// Written by Nathan Sherburn
// Created 18 July 2012
// Check status of lock at Quiz session begin

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];
$status = $_SESSION['status'];
$id = $_SESSION['id'];

// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

// Find out if currently locked or not
$sql_resource = mysql_query("SELECT LOCKED FROM lecturer_ques WHERE id='$id'") or die(mysql_error());;
$locked_row = mysql_fetch_row($sql_resource);
$locked = $locked_row[0];

// Echo lock status
echo ($locked);

mysql_close($dbcon);
?>