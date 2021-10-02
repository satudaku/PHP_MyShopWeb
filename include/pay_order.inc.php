<!-- Php script for pay_order page -->
<?php
session_start();

# Throw out user if not logged in to login page
if (!isset($_SESSION["id"])) {
    header("location: ../login.php?user=login_first");
    die;
}

if (isset($_POST["submit"])) {
    $id = $_SESSION["id"];
    $order_id = $_POST["order_id"];

    require_once 'dbh.inc.php';
    require_once 'function.inc.php';

    # Input error handling
    if (empty_input_pay($order_id) !== false) {
        header("location: ../pay_order.php?error=empty_input");
        die();
    }

    pay_order($conn, $id, $order_id);

}
echo "im here";
die();