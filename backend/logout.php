<?php
session_start();
session_unset();
session_destroy();

header("Location: /FESTIVO/Login Page/login.html");
exit();
?>