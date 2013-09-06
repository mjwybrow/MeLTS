<?php
// Written by Nathan Sherburn
// Created 5 September 2013
// To Check whether the current session has been alive for over 2 hours (7200 sec)

session_start();

if ($_SESSION['LOGGEDIN'] == null){
	// check if page viewer is currently logged in
	header('Location: index.html');
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
	// check if last request was more than 2h ago
	session_unset();     // unset $_SESSION variable for the run-time (frees all session variables currently registered)
	session_destroy();   // destroy session data in storage (destroys all of the data associated with the current session)
	// set database logged_in flag to 0
	header('Location: index.html');
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

session_write_close(); // prevent conflicts with other php sessions

?>