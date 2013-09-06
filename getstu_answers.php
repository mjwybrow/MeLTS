<?php
// Written by Shea Yuin Ng
// Created 22 October 2012
// For lecturers to view the results of the posted question

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, unit code and unit name from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];
$unit_name = $_SESSION['unit_name'];
$id = $_SESSION['id'];

// Select database to connect
mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

$table_name='q_'.$id;

$btnA = 'btnA';
$result = mysql_query("SELECT * FROM $table_name WHERE mcq_answer = '$btnA'", $dbcon);
$cntA = mysql_num_rows($result); 
$btnB = 'btnB';
$result = mysql_query("SELECT * FROM $table_name WHERE mcq_answer = '$btnB'", $dbcon);
$cntB = mysql_num_rows($result); 
$btnC = 'btnC';
$result = mysql_query("SELECT * FROM $table_name WHERE mcq_answer = '$btnC'", $dbcon);
$cntC = mysql_num_rows($result); 
$btnD = 'btnD';
$result = mysql_query("SELECT * FROM $table_name WHERE mcq_answer = '$btnD'", $dbcon);
$cntD = mysql_num_rows($result); 

$total = $cntA+$cntB+$cntC+$cntD;

// Output data in XML format
header("Content-type: text/xml"); //Declare saving data in XML form
echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
echo "<MCQAnswer>"; //Top root directory

// Print each element in XML
echo "<Answer>";
echo "<CntA>$cntA</CntA>";
echo "<CntB>$cntB</CntB>";
echo "<CntC>$cntC</CntC>";
echo "<CntD>$cntD</CntD>";
echo "<Total>$total</Total>";
echo "</Answer>";
echo "</MCQAnswer>";

// Close connection to mySOL
mysql_close($dbcon);
?>