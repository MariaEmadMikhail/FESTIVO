<?php
include "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = trim($_POST["password"]);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $phone = $_POST["phone"];
    $age = $_POST["age"];

    $sql = "INSERT INTO customer 
    (first_name, last_name, username, email, password, phone, age)
    VALUES 
    ('$first_name', '$last_name', '$username', '$email', '$hashed_password', '$phone', '$age')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../Login Page/login.html");
    exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>