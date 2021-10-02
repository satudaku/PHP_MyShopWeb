<!-- Php script for success create order page -->
<?php

$order_id = $_SESSION["order_id"];

require_once 'dbh.inc.php';
require_once 'function.inc.php';

# Find row in  order_status table
$last_order = find_status_order($conn, $order_id);
if ($last_order === false) {
    header("location: ../topup_balance.php?error=cannot_find_order");
    die();
}

# Define variables of the order row
$order_placed_time = $last_order["order_placed_time"];
$payment_due_time = $last_order["payment_due_time"];
$order_status = $last_order["order_status"];
$shipping_code = $last_order["shipping_code"];
$order_topup_id = $last_order["order_topup_id"];
$order_product_id = $last_order["order_product_id"];

# Add more variables for order with order_topup_id
if ($order_topup_id !== NULL) {
    # Find row in topup_order table
    $last_topup = find_topup_order($conn, $order_topup_id);
    if ($last_topup === false) {
        header("location: ../topup_balance.php?error=cannot_find_order");
        die();
    }
    else {
        $mobile_no = $last_topup["mobile_no"];
        $balance_value = $last_topup["balance_value"];
    }
}

# Add more variables for order with order_product_id
if ($order_product_id !== NULL) {
    # Find row in product_order table
    $last_product = find_product_order($conn, $order_product_id);
    if ($last_product === false) {
        header("location: ../topup_balance.php?error=cannot_find_order");
        die();
    }
    else {
        $product = $last_product["product"];
        $product_price = $last_product["product_price"];
        $shipping_address = $last_product["shipping_address"];
    }
}

# When pay now button pressed send user to pay_order page
if (isset($_POST["submit"])) {
    echo "imhere";
    header("location: pay_order.php?order_id=$order_id");
    exit();
}
// echo "order placed time: " . $order_placed_time . "\n" . "payment due: " . $payment_due_time . "\n" . "status: " . $order_status . "\n" . "topup id:" . $order_topup_id . "</BR>";
// echo "product: ". $product ."</br>";
// echo "product_price: ". $product_price ."</br>";
// echo "shipping_address: ". $shipping_address ."</br>";
