<?php
// Written by Shea Yuin Ng
// Created 26 April 2013
// To get result for understanding scale

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];
$unit_name = $_SESSION['unit_name'];

// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

$result = mysql_query("SELECT * FROM student_list WHERE u_scale = 'Y'", $dbcon);
$cntY = mysql_num_rows($result); 
$result = mysql_query("SELECT * FROM student_list WHERE u_scale = 'N'", $dbcon);
$cntN = mysql_num_rows($result); 

$total = $cntY+$cntN;

// Output data in XML format
header("Content-type: text/xml"); //Declare saving data in XML form
echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
echo "<UScale>"; //Top root directory

// Print each element in XML
echo "<Count>";
echo "<CntY>$cntY</CntY>";
echo "<CntN>$cntN</CntN>";
echo "<Total>$total</Total>";
echo "</Count>";
echo "</UScale>";

// Close connection to mySOL
mysql_close($dbcon);
?>