<!-- Php script for prepaid balance page -->
<?php
session_start();

if (!isset($_SESSION["id"])){
    header("location: ../login.php?error=login_first");
}

if (isset($_POST["submit"])) {
    $id = $_SESSION["id"];
    $mobile_no = $_POST["mobile_no"];
    $balance_value = $_POST["balance_value"];

    require_once 'dbh.inc.php';
    require_once 'function.inc.php';

    # Input error handling
    if (empty_input_topup($mobile_no, $balance_value) !== false) {
        header("location: ../topup_balance.php?error=empty_input");
        die();
    }

    topup_balance($conn, $id, $mobile_no, $balance_value);

}
echo "im here";
die();