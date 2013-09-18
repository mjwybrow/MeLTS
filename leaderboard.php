<?php
// Written by Nathan Sherburn
// Created 16 August 2013
// Tally up the results of the student answers

// Connect to mySQL
include('connections.php');

// Connect to the database
mysql_select_db($unit_chosen, $dbcon) or die("Cannot select unit database!");

// Print table
echo "
<table border='0' width='100%'>
<tr>
<th align=\"left\">Nickname</th>
<th align=\"left\">Score</th>
</tr>";

// Select all students and their scores
$score_resource = mysql_query("SELECT * FROM student_list ORDER BY score DESC;") or die("Cannot get student list");

// Fill the table
while($score_row = mysql_fetch_array($score_resource)){
	$username = $score_row['username'];
	mysql_select_db('main_database', $dbcon) or die("Cannot select main database!");
	$nickname_resource = mysql_query("SELECT * FROM account WHERE username = '$username'");	
	$nickname_row = mysql_fetch_array($nickname_resource);
	echo "<tr>";
	echo "<td align=\"left\">" . $nickname_row['nickname'] . "</td>";
	echo "<td align=\"left\">" . $score_row['score'] . "</td>";
	echo "</tr>";
}
echo "</table>";

mysql_close($dbcon);
?>