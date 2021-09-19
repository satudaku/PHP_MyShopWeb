<!-- Customer registration -->
<?php

if (isset($_POST["submit"])) {
    
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    echo "email1: " . $email . "</br>";
    echo "password1: " . $password . "</br>";

    require_once 'dbh.inc.php';
    require_once 'function.inc.php';

    if (empty_input_register($email, $password) !== false) {
        header("location: ../register.php?error=empty_input");
        exit();
    }
    if (invalid_email($email) !== false) {
        header("location: ../register.php?error=invalid_email");
        exit();
    }
    if (email_exist($conn, $email) !== false) {
        header("location: ../register.php?error=email_already_exist");
        exit();
    }
    if (invalid_password($password) !== false) {
        header("location: ../register.php?error=invalid_password");
        exit();
    }

    create_customer($conn, $name, $email, $password);
}