<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPW = "";
$dbName = "ul";
$mysqli = mysqli_connect($hostname, $db_username, $db_password, $db_name);
if(mysqli_connect_errno()){
	die("Failed to connect to MySQL: " . mysqli_connect_error());
}