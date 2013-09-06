<?php
// Written by Shea Yuin Ng
// Created 11 September 2012
// To logout

// Resume session from previous session
session_start();

// Destroy session when logging out
session_unset();     // unset $_SESSION variable for the run-time (frees all session variables currently registered)
session_destroy();   // destroy session data in storage (destroys all of the data associated with the current session)

echo("Log out successful");
?>