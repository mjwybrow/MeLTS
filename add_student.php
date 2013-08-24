<?php
// Written by Shea Yuin Ng, Nathan Sherburn
// Created 14 March 2013
// For lecturers to add students into the student list of a unit

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Get username, first name, last name, email and status 
$stud_name = mysql_real_escape_string($_POST['stud_name']);
$stud_fname = mysql_real_escape_string($_POST['fname']);
$stud_lname = mysql_real_escape_string($_POST['lname']);
$stud_email = mysql_real_escape_string($_POST['email']);
$status = $_POST['status'];
$pswd = "12345";
$pswd = md5($pswd);

// Change username to all lowercase characters
$stud_name = strtolower($stud_name);

// Get username from session variable
$uname = $_SESSION['uname'];
$unit_code = $_SESSION['unit_chosen'];

// Connect to database and insert into the database
 $database_name = $unit_code.'_'.$uname;
 mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");

// Check whether the username already existed
$sql="SELECT * FROM student_list WHERE username = '$stud_name'";
$r = mysql_query($sql);

// If error in selecting table
if(!$r) {
	$err=mysql_error();
	print $err;
	exit();
}

// If student not registered
if(mysql_affected_rows()==0){//name does not exist in unit database
	// Check main database whether name already registered
	// Connect to database and insert into the database
	mysql_select_db("main_database",$dbcon) or die("Cannot select main database!");

	// Check whether the username already existed
	$sql="SELECT * FROM account WHERE username = '$stud_name' and status = 'S'";
	$r = mysql_query($sql);

	// If error in selecting table
	if(!$r) {
		$err=mysql_error();
		print $err;
		exit();
	}
	
	if(mysql_affected_rows()==0){// If no then add into main list of users
		mysql_query("INSERT INTO account(username, password, first_name, last_name, status, email) VALUES('$stud_name','$pswd','$stud_fname','$stud_lname','$status','$stud_email')")  or die("Account not created!! Username might contain more than 20 characters!!");
		mysql_query("INSERT INTO students(username, unit1, unit2, unit3, unit4, unit5) VALUES('$stud_name','$unit_code','','','','')")  or die("Account not created!!");
		mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");
		mysql_query("INSERT INTO student_list(username, nickname, first_name, last_name, u_scale) VALUES('$stud_name', 'anonymous','$stud_fname','$stud_lname','0')")  or die("Student cannot be added!!");
		echo("1");
	}
	else{// If yes then just update the units taken by student and insert the student into the student list in unit database
		// Get the existing details of students
		$get_details="SELECT * FROM account WHERE username = '$stud_name' and status = 'S'";
		// Get ID of the array
		$query_details = mysql_query($get_details)  or die("Cannot query details!!");
		// Get the whole row of information of the user
		$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!!");
		// Extract 'unit2','unit3','unit4' and 'unit5' field from the array
		$stud_fname = $fetch_details['first_name'];
		$stud_lname = $fetch_details['last_name'];

		// Get the details of login username
		$get_details="SELECT * FROM students WHERE username = '$stud_name'";
		// Get ID of the array
		$query_details = mysql_query($get_details)  or die("Cannot query details!!");
		// Get the whole row of information of the user
		$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!!");
		// Extract 'unit1','unit2','unit3','unit4' and 'unit5' field from the array
		$unit1 = $fetch_details['unit1'];
		$unit2 = $fetch_details['unit2'];
		$unit3 = $fetch_details['unit3'];
		$unit4 = $fetch_details['unit4'];
		$unit5 = $fetch_details['unit5'];
		
		// Insert unit code into the record of what units the students are taking
		if($unit1 == $unit_code or $unit2 == $unit_code or $unit3 == $unit_code or $unit4 == $unit_code or $unit5 == $unit_code){
			mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");
			mysql_query("INSERT INTO student_list(username, nickname, first_name, last_name, u_scale) VALUES('$stud_name', 'anonymous','$stud_fname','$stud_lname','0')")  or die("Student cannot be added!!");
			echo("1");
		}
		elseif($unit1 == ""){
			mysql_query("UPDATE students SET unit1 = '$unit_code' WHERE username='$stud_name'");
			mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");
			mysql_query("INSERT INTO student_list(username, nickname, first_name, last_name, u_scale) VALUES('$stud_name', 'anonymous','$stud_fname','$stud_lname','0')")  or die("Student cannot be added!!");
			echo("1");
		}
		elseif($unit2 == ""){
			mysql_query("UPDATE students SET unit2 = '$unit_code' WHERE username='$stud_name'");
			mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");
			mysql_query("INSERT INTO student_list(username, nickname, first_name, last_name, u_scale) VALUES('$stud_name', 'anonymous','$stud_fname','$stud_lname','0')")  or die("Student cannot be added!!");
			echo("1");
		}
		elseif($unit3 == ""){
			mysql_query("UPDATE students SET unit3 = '$unit_code' WHERE username='$stud_name'");
			mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");
			mysql_query("INSERT INTO student_list(username, nickname, first_name, last_name, u_scale) VALUES('$stud_name', 'anonymous','$stud_fname','$stud_lname','0')")  or die("Student cannot be added!!");
			echo("1");
		}
		elseif($unit4 == ""){
			mysql_query("UPDATE students SET unit4 = '$unit_code' WHERE username='$stud_name'");
			mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");
			mysql_query("INSERT INTO student_list(username, nickname, first_name, last_name, u_scale) VALUES('$stud_name', 'anonymous', '$stud_fname','$stud_lname','0')")  or die("Student cannot be added!!");
			echo("1");
		}
		elseif($unit5 == ""){
			mysql_query("UPDATE students SET unit5 = '$unit_code' WHERE username='$stud_name'");
			mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");
			mysql_query("INSERT INTO student_list(username, nickname, first_name, last_name, u_scale) VALUES('$stud_name', 'anonymous', '$stud_fname', '$stud_lname','0')")  or die("Student cannot be added!!");
			echo("1");
		}
		else{
			echo("Student exceeded the limited number of units");
		}
	}
}
else{
	echo("0");
}

// Close connection to mySOL
mysql_close($dbcon);
?>