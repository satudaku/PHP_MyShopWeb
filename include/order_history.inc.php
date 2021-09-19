<?php

require_once 'dbh.inc.php';
require_once 'function.inc.php';

$id = $_SESSION['id'];

# Get order data
$get_order = get_order($conn, $id);
# If order data empty return error message
if ($get_order === false){
    header("location: ../order_history.php?error=cannot_find_order1");
    die();
}

$count = 0;
foreach ($get_order as $row) {
    echo "<tr>";
    $status = $row["order_status"];
    $order_id = $row["order_id"];
    # If product_id is present
    if ($row["order_product_id"] != null) {
        $last_product = find_product_order($conn, $row["order_product_id"]);
        if ($last_product === false) {
            header("location: ../topup_balance.php?error=cannot_find_order2");
            die();
        }
        else {
            # Define variables
            $product = $last_product["product"];
            $product_price = $last_product["product_price"];
            $shipping_address = $last_product["shipping_address"];
            echo "<td>" . $order_id . "\n Rp." . $product_price . "</br>";
            echo "Product: " . $product . "\n| Shipping address: " . $shipping_address . "</td>"; 
        }
    }
    # If topup_id is present
    else if ($row["order_topup_id"] != null) {
        $last_topup = find_topup_order($conn, $row["order_topup_id"]);
        if ($last_topup === false){
            header("location: ../topup_balance.php?error=cannot_find_order3");
            die();
        }
        else {
            # Define variables
            $mobile_no = $last_topup["mobile_no"];
            $balance_value = $last_topup["balance_value"];
            echo "<td>" . $order_id . "\n Rp." . $balance_value . "</br>";
            echo "Prepaid balance topup for mobile number: " . $mobile_no . "</td>";
        }
    }
    if ($status == "cancelled") {
        echo "<td>Cancelled</td>";
    }
    else if ($status == "failed") {
        echo "<td>Failed</td>";
    }
    else if ($status == "unpaid") {
        echo "<td><input class='input-button' href='pay_order.php'></td>";
    }
    else if ($status == "paid" && (!empty($get_order['shipping_code']))) {
        echo "<td>Shipping code: " . $get_order['shipping_code'] . "</td>";
    }
    else {
        echo "<td>success</td>";
    }
    echo "<hr>";
}
