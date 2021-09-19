<!-- Php script for prepaid balance page -->
<?php
session_start();

if (!isset($_SESSION["id"])){
    header("location: ../login.php?error=login_first");
}

if (isset($_POST["submit"])) {

    $id = $_SESSION["id"];
    $product = $_POST["product"];
    $shipping_address = $_POST["shipping_address"];
    $product_price = $_POST["product_price"];

    require_once 'dbh.inc.php';
    require_once 'function.inc.php';

    # Input error handling
    if (empty_input_product($product, $shipping_address, $product_price) !== false) {
        header("location: ../product_page.php?error=empty_input");
        die();
    }

    order_product($conn, $id, $product, $shipping_address, $product_price);

}
echo "im here";
die();