<?php
// Written by Shea Yuin Ng
// Created 19 September 2012
// For lecturers to delete unit

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username from session variable
$uname = $_SESSION['uname'];
$unit_chosen = $_SESSION['unit_chosen'];
$unit_name = $_SESSION['unit_name'];

// Check if another unit have the same unit code in units
// Connect to main database
mysql_select_db("main_database",$dbcon) or die("Cannot select database!");

// Delete the unit from the list of units
$sql="DELETE FROM units WHERE  unit_code='$unit_chosen' and lecturer = '$uname'";
$r = mysql_query($sql) or die("Unit cannot be deleted!");

// Check if another unit has the same unit code in units
$query=mysql_query("SELECT * FROM units WHERE unit_code = '$unit_chosen'")or die("Cannot access table!");

//If no another unit has the same unit code
if(mysql_affected_rows()==0){
	//Access the student list of the unit to be deleted
	mysql_select_db($unit_chosen, $dbcon) or die("Cannot select unit database!");
	$query_stud_list=mysql_query("SELECT * FROM student_list") or die("Cannot access table!");
	
	// If there are students	
	if(mysql_affected_rows()!=0){
		//Loop for each student
		while ($row_stud_list = mysql_fetch_array($query_stud_list)) {
			// Get student's username
			$stud_name = $row_stud_list['username'];
			
			//Access students in main database
			mysql_select_db("main_database", $dbcon) or die("Cannot select database!");
			$get_details="SELECT * FROM students WHERE username = '$stud_name'";
			
			// Search for the unit code
			// Get ID of the array
			$query_details = mysql_query($get_details)  or die("Cannot query details!");
			// Get the whole row of information of the user
			$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
			// Extract 'unit1','unit2','unit3','unit4' and 'unit5' field from the array
			$unit1 = $fetch_details['unit1'];
			$unit2 = $fetch_details['unit2'];
			$unit3 = $fetch_details['unit3'];
			$unit4 = $fetch_details['unit4'];
			$unit5 = $fetch_details['unit5'];
			
			// Delete unit code from the record of what units the students are taking
			if($unit1 == $unit_chosen){
				mysql_query("UPDATE students SET unit1 = '' WHERE username='$stud_name'") or die("Cannot delete the unit from student!");
			}
			elseif($unit2 == $unit_chosen){
				mysql_query("UPDATE students SET unit2 = '' WHERE username='$stud_name'") or die("Cannot delete the unit from student!");
			}
			elseif($unit3 == $unit_chosen){
				mysql_query("UPDATE students SET unit3 = '' WHERE username='$stud_name'") or die("Cannot delete the unit from student!");
			}
			elseif($unit4 == $unit_chosen){
				mysql_query("UPDATE students SET unit4 = '' WHERE username='$stud_name'") or die("Cannot delete the unit from student!");
			}
			elseif($unit5 == $unit_chosen){
				mysql_query("UPDATE students SET unit5 = '' WHERE username='$stud_name'") or die("Cannot delete the unit from student!");
			}
			else{
				echo("Error");
			}
		}
	}	
}

// Drop the deleted unit's database
$sql = 'DROP DATABASE '.$unit_chosen;
if (mysql_query($sql, $dbcon)) {
    echo("Unit successfully deleted!");
} else {
    echo 'Error dropping database: ' . mysql_error() . "\n";
}

// Close connection to mySOL
mysql_close($dbcon);
?>