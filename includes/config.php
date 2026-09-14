<?php
// ============================================
// Soundphere — Database connection
// Edit these if your MySQL username/password is different
// (default XAMPP settings: user=root, password="")
// ============================================

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "soundphere";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error() .
        "<br>Make sure you imported database.sql in phpMyAdmin first.");
}

// Start session on every page that includes this file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
