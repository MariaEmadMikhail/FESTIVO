<?php
session_start();
include "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM customer WHERE username='$username'";
    $result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row["password"])) {

        $_SESSION["username"] = $row["username"]; 

        header("Location: /FESTIVO/customer_home/index.php");
        exit();
    } else {
        header("Location: /FESTIVO/Login Page/login.html?error=1");
        exit();
    }
} else {
    header("Location: /FESTIVO/Login Page/login.html?error=1");
    exit();
}
}
?>    