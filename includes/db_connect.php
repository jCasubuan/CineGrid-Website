<?php

$DBHost = "localhost";
$DBUser = "root";
$DBPass = "";
$DBName = "cinegrid_db";

$Conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName);

if (!$Conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>