<?php
// Written by Shea Yuin Ng
// Created 18 March 2013
// For students to view list  of units after the lecturers added them  

//THIS CAN BE SIMPLIFIED NOW THAT LECTURERS SHARE DATABASES

// Resume session from previous session
session_start();

// Connect to mySQL
include('connections.php');

// Select database to connect
mysql_select_db("main_database",$dbcon) or die("Cannot select database!");

// Get username from session variable
$uname = $_SESSION['uname'];

// Check whether the username for the unit already existed
$sql="SELECT unit1, unit2,unit3,unit4,unit5 FROM students WHERE username = '$uname'";
$r = mysql_query($sql);

// If error in selecting table
if(!$r) {
	$err=mysql_error();
	print $err;
	exit();
}

if(mysql_affected_rows()==0){//no units exist in database
	// Output data in XML format
	header("Content-type: text/xml"); //Declare saving data in XML form
	echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
	echo "<Units>"; //Top root directory
	echo "<Unit>";
	echo "<UnitCode>0</UnitCode>";
	echo "<UnitName>0</UnitName>";
	echo "<LecName>0</LecName>";
	echo "</Unit>";
	echo "</Units>";
}
else{
	// Output data in XML format
	header("Content-type: text/xml"); //Declare saving data in XML form
	echo '<?xml version="1.0" encoding="utf-8"?>'; // Print XML tag
	echo "<Units>"; //Top root directory

	// Get the whole row of information of the unit
	$fetch_details = mysql_fetch_array($r) or die("Cannot fetch details!");
	//Get each element
	$unit1_code = $fetch_details["unit1"];
	$unit2_code = $fetch_details["unit2"];
	$unit3_code = $fetch_details["unit3"];
	$unit4_code = $fetch_details["unit4"];
	$unit5_code = $fetch_details["unit5"];
	$unit_code = array($unit1_code, $unit2_code, $unit3_code, $unit4_code, $unit5_code);
	
	for ($i=0; $i<5; $i++){
		if($unit_code[$i]!=""){
			// Check if there is more than one lecturer for the unit
			mysql_select_db("main_database",$dbcon) or die("Cannot select database!");
			$query=mysql_query("SELECT * FROM units WHERE unit_code = '$unit_code[$i]'")or die("Cannot access table!");
			
			//If no, 
			if(mysql_affected_rows()==1){
				// Get the whole row of information of the unit
				$fetch_details = mysql_fetch_array($query) or die("Cannot fetch details!");
				// Extract 'unit_name' and 'lecturer' field from the array
				$unit_name = htmlspecialchars($fetch_details['unit_name']);
				$unit_lecname = htmlspecialchars($fetch_details['lecturer']);
				$unit_code[$i] = htmlspecialchars($unit_code[$i]);

				// Print each element in XML
				echo "<Unit>";
				echo "<UnitCode>$unit_code[$i]</UnitCode>";
				echo "<UnitName>$unit_name</UnitName>";
				echo "<LecName>$unit_lecname</LecName>";
				echo "</Unit>";
			}
			else{
				// Get the lecturer's name/s
				$j=0;
				 while ($row = mysql_fetch_array($query)) {
					// Get lecturer's name
					$lecturer[$j] = $row['lecturer'] ;
					$j++;
				}//while fetch lecturer's name
				
				// For every lecturer teaching the same unit
				for ($k=0; $k<$j; $k++){
					$lecturer_uname = $lecturer[$k];
					
					//Check if student's name in the unit's student list
					$database_name = $unit_code[$i];
					mysql_select_db($database_name,$dbcon) or die("Cannot select unit database!");
					$query_stud_list=mysql_query("SELECT * FROM student_list WHERE username = '$uname'")or die("Cannot access table!");
					
					//If yes	
					if(mysql_affected_rows()!=0){
						mysql_select_db("main_database",$dbcon) or die("Cannot select database!");
						// Get the details of the unit
						$get_details="SELECT * FROM units WHERE unit_code = '$unit_code[$i]' and lecturer = '$lecturer_uname'";
						// Get ID of the array
						$query_details = mysql_query($get_details)  or die("Cannot query details!");
						// Get the whole row of information of the unit
						$fetch_details = mysql_fetch_array($query_details) or die("Cannot fetch details!");
						// Extract 'unit_name' field from the array
						$unit_name = htmlspecialchars($fetch_details['unit_name']);
						$lecturer_uname = htmlspecialchars($lecturer_uname);
						$unit_code[$i] = htmlspecialchars($unit_code[$i]);

						// Print each element in XML
						echo "<Unit>";
						echo "<UnitCode>$unit_code[$i]</UnitCode>";
						echo "<UnitName>$unit_name</UnitName>";
						echo "<LecName>$lecturer_uname</LecName>";
						echo "</Unit>";
					}
				}
			}
		}
	}
		
	echo "</Units>";
}

// Close connection to mySOL
mysql_close($dbcon);
?>