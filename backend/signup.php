<?php
session_start();
include "db_connection.php";

$data = json_decode(file_get_contents("php://input"), true);

// STEP 1: SEND OTP
if (isset($data['action']) && $data['action'] === 'send_otp') {

    $_SESSION["signup_data"] = [
        "first_name" => $data["first_name"],
        "last_name" => $data["last_name"],
        "username" => $data["username"],
        "email" => $data["email"],
        "password" => password_hash($data["password"], PASSWORD_DEFAULT),
        "phone" => $data["phone"],
        "age" => $data["age"]
    ];

    $_SESSION["otp"] = rand(100000, 999999);

    echo json_encode(["status" => "otp_sent"]);
    exit();
}

// STEP 2: VERIFY OTP
if (isset($data['action']) && $data['action'] === 'verify_otp') {

    if ($data["otp"] == $_SESSION["otp"]) {

        $user = $_SESSION["signup_data"];

        $sql = "INSERT INTO customer 
        (first_name, last_name, username, email, password, phone, age)
        VALUES 
        ('{$user["first_name"]}', '{$user["last_name"]}', '{$user["username"]}',
         '{$user["email"]}', '{$user["password"]}', '{$user["phone"]}', '{$user["age"]}')";

        if ($conn->query($sql)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "db_error"]);
        }

    } else {
        echo json_encode(["status" => "invalid"]);
    }

    exit();
}
?>