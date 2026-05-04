<?php
$conn = new mysqli("localhost", "root", "", "festivo_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>