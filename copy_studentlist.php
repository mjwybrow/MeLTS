<?php
// Written by Shea Yuin Ng
// Created 21 January 2013
// For lecturers to add students from the same unit under another lecturer

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

//Get lecturer's uname
$stud_list = mysql_real_escape_string($_POST['stud_list']);

// Get username from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];

// Unit database name
$copydatabase_name = $unit_code.'_'.$stud_list;

// Connect to unit database and insert into the database
mysql_select_db($copydatabase_name,$dbcon) or die("Cannot select unit database!");

// Access the student list of the unit
$query = "SELECT * FROM student_list";
$get_studlist = mysql_query($query) or die("Student list cannot be found!");

$i = 1;
if(mysql_affected_rows()!=0){//If the list is not empty
	while ($studlist_array = mysql_fetch_array($get_studlist)) {
		// Get details of every student
		$stud_name = $studlist_array['username'];
		$stud_fname = mysql_real_escape_string($studlist_array['first_name']);
		$stud_lname = mysql_real_escape_string($studlist_array['last_name']);
		
		// Connect to unit database and insert into the database
		mysql_select_db($unit_code, $dbcon) or die("Cannot select unit database!");

		// Check whether the username already existed
		$sql="SELECT * FROM student_list WHERE username = '$stud_name'";
		$r = mysql_query($sql) or die("Cannot select a specific student!");;

		// If student not registered
		if(mysql_affected_rows()==0){//name does not exist in unit database
			mysql_query("INSERT INTO student_list(username, first_name, last_name, u_scale) VALUES('$stud_name', '$stud_fname','$stud_lname','0')")  or die("Student cannot be added!");
		}
		else{
			echo("No.$i: '$stud_name' is already registered in this unit.\n");
			$i++;
		}
	}
}
else{
	echo("The student list is empty.");
}

// Close connection to mySOL
mysql_close($dbcon);
?>