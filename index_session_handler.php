<?php
// Written by Nathan Sherburn
// Created 18 September 2013
// To Check whether the user has already signed in

session_start();

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] == 1 && isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] < 7200)) {
	// check if last request was more than 2h ago
	if ($_SESSION['status'] == 'L')
		header('Location: lecturer_homepage.html');
	else
		header('Location: student_homepage.html');
}

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

session_write_close(); // prevent conflicts with other php sessions

?>