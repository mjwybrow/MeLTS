<?php
// Written by Nathan Sherburn
// Created 18 September 2013
// For students to change their alias on the leaderboard

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username from session variable
$uname = $_SESSION['uname'];

// Connect to database and insert into the database
mysql_select_db("main_database", $dbcon) or die("Cannot select main database!");

$alias_resource = mysql_query("SELECT nickname FROM account WHERE username = '$uname'") or die ("Could not find your alias");
$alias = mysql_fetch_array($alias_resource);

echo $alias[0];

// Close connection to mySOL
mysql_close($dbcon);
?>