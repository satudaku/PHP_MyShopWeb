<!-- Php script to login customer into member only pages-->
<?php
session_start();

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    require_once 'dbh.inc.php';
    require_once 'function.inc.php';

    if (empty_input_login($email, $password) !== false) {
        header("location: ../login.php?error=empty_input");
        die();
    }

    login_customer($conn, $email, $password);
}

