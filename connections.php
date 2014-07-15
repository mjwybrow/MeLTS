<?php
// Written by Shea Yuin Ng
// Created 20 August 2012
// To connect to the database

$hostname_localhost ="127.0.0.1";
//$database_localhost ="mydatabase";
$username_localhost ="root";
$password_localhost ="vbn123"; 

//connect to mySQL
$dbcon = mysql_connect($hostname_localhost,$username_localhost,$password_localhost);

////Check whether connection is successful
// if (!$dbcon){
	// die('error connecting to database');
// }
// echo'Connection successful';
?>
