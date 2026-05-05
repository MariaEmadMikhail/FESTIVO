<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/PHPMailer/Exception.php';
require '../vendor/PHPMailer/PHPMailer.php';
require '../vendor/PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data["email"];

    // generate OTP
    $otp = rand(100000, 999999);
    $_SESSION["otp"] = $otp;
    $_SESSION["email"] = $email;

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mariaemad234@gmail.com'; // YOUR EMAIL
        $mail->Password = 'jdfk kpgp dzjr nwgd'; // YOUR APP PASSWORD
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email content
        $mail->setFrom('your_email@gmail.com', 'Festivo');
        $mail->addAddress($email);

        $mail->Subject = 'Your Festivo Verification Code';
        $mail->Body = "Your OTP code is: $otp";

        $mail->send();

        echo json_encode(["status" => "otp_sent"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error"]);
    }
}
?>