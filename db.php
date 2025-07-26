<?php
$servername = "sql204.infinityfree.com";
$username = "if0_39184492";
$password = "Salamatpura123";
$dbname = "if0_39184492_mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

// ✅ Fix Urdu character display
$conn->set_charset("utf8mb4");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
