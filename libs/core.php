<?php
session_start();
include 'config.php';
include 'database.php';
include 'function.php';
$fap_version = 1;
$time = time();
$ip = mysqli_real_escape_string($mysqli, $_SERVER['REMOTE_ADDR']);