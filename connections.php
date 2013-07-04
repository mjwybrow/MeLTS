<?php
// Written by Shea Yuin Ng
// Created 20 August 2012
// To connect to the database

$hostname_localhost ="localhost";
//$database_localhost ="mydatabase";
$username_localhost ="root";
$password_localhost =""; 

//connect to mySQL
$dbcon = mysql_connect($hostname_localhost,$username_localhost,$password_localhost);

////Check whether connection is successful
// if (!$dbcon){
	// die('error connecting to database');
// }
// echo'Connection successful';
?>
