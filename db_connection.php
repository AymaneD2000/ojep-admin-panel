<?php
$host = 'localhost';  // This is usually 'localhost' for phpMyAdmin
$user = 'root';  // Your phpMyAdmin username
$password = '';  // Your phpMyAdmin password
$dbname = 'ojep_db';  // The database name you created or are using

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Do not close the connection here
// The connection will be closed after running queries in the relevant files
?>
