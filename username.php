<?php
// Written by Shea Yuin Ng and Nathan Sherburn
// Created 11 September 2014 (and check authentication)
// Get the first name to be printed on the page header 

// Resume session from previous session
session_start();

$status = $_SESSION['status'];
echo($status);

?>