<?php

require_once 'dbh.inc.php';
require_once 'function.inc.php';

$id = $_SESSION['id'];

# Check if the page number is specified and check if it's a number, if not return the default page number which is 1.
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

# Number of results to show on each page.
$num_result_on_page = 5;

# If the user uses search bar
if (isset($_GET['search'])) {
    # If 'no result' error parameter was given
    if (isset($_GET['error']) && $_GET['error'] === 'no_result') {
        echo "<p class='error'>No order found!</p>";
    }
    if (empty($_GET['search'])) {
        header('location:order_history.php');
        exit();
    }
    # Check weather the keyword is valid interger
    $checked_keyword = invalid_search_order($_GET['search']);
    if ($checked_keyword == false) {
        header('location:order_history.php?error=invalid_search');
        exit();
    }
    else {
        $keyword = "%{$checked_keyword}%"; # Searched keyword variable
        # Get the total number of searched records
        $count_order = count_search($conn, $id, $keyword);
        if ($count_order > 0) {
            # Get order data
            $get_order = search_order($conn, $id, $keyword, $page, $num_result_on_page);
        }
        else {
            $get_order = false;
        }
    }
}
# Else show all records with given SESSION ID
else {
    # If 'no result' error parameter was given alert no order found
    if (isset($_GET['error']) && $_GET['error'] === 'no_result') {
        echo "<p class='error'>No order found!</p>";
    }
    else {
        # Get the total number of records
        $count_order = count_order($conn, $id);
        if ($count_order > 0) {
            # Get order data
            $get_order = get_order($conn, $id, $page, $num_result_on_page);
        }
        else {
            header("location:order_history.php?error=no_result");
            exit();
        }
    }
}


# If input wrong character
if (isset($_GET['error']) && $_GET['error'] === 'invalid_search') {
    echo "<p class='error'>Invalid input! Numbers only.</p>";
}
else if (!isset($_GET['error'])) {
    # If order data empty return error message
    if ($get_order == false){
        header("location:order_history.php?search=" . $_GET['search'] . "&error=no_result");
    }
    else {
        # Define table for order list
        echo "<table class='table-history'>";
        foreach ($get_order as $row) {
            echo "<tr>";
            $status = $row["order_status"];
            $order_id = $row["order_id"];
            $shipping_code = $row["shipping_code"];
            # If product_id is present
            if ($row["order_product_id"] != null) {
                $last_product = find_product_order($conn, $row["order_product_id"]);
                if ($last_product === false) {
                    header("location:order_history.php?error=cannot_find_order2");
                    die();
                }
                else {
                    # Define variables
                    $product = $last_product["product"];
                    $product_price = $last_product["product_price"];
                    $shipping_address = $last_product["shipping_address"];
                    echo "<td class='order-info'>" . $order_id . "\n<span class='price'>Rp." . $product_price . "</span></br>";
                    echo "Product: " . $product . "\n| Shipping address: " . $shipping_address . "</td>"; 
                }
            }
            # If topup_id is present
            else if ($row["order_topup_id"] != null) {
                $last_topup = find_topup_order($conn, $row["order_topup_id"]);
                if ($last_topup === false){
                    header("location:order_history.php?error=cannot_find_order3");
                    die();
                }
                else {
                    # Define variables
                    $mobile_no = $last_topup["mobile_no"];
                    $balance_value = $last_topup["balance_value"];
                    echo "<td class='order-info'>" . $order_id . "\n<span class='price'>Rp." . $balance_value . "</span></br>";
                    echo "Prepaid balance topup for mobile number: " . $mobile_no . "</td>";
                }
            }
            # Status 
            if ($status === "cancelled") {
                echo "<td><p class='cancelled'>Cancelled</p></td>";
            }
            else if ($status === "failed") {
                echo "<td><p class='failed'>Failed</td></p>";
            }
            else if ($status === "unpaid") {
                echo "<td><form action='pay_order.php?order_id=" . $order_id . "' method='post'>
                    <input class='pay-button' type='submit' value='Pay now' name='submit'>
                    </form></td>";
            }
            else if ($status === "paid" && (!empty($shipping_code))) {
                echo "<td><p>Shipping code: <span class='code'> " . $shipping_code . " </span></p></td>";
            }
            else {
                echo "<td><p class='success'>Success</p></td>";
            }
            echo "</tr>";
        }
        echo "</table>";

        # Pagination
        if (ceil($count_order / $num_result_on_page) > 0){
            echo "<ul class='pagination'>";
            if ($page > 1 && isset($_GET['search'])) {
                echo "<li class='prev'><a href='order_history.php?search=" . $_GET['search'] . "&page=" . $page - 1 . "'>Prev</a></li>";
            }
            else if ($page > 1) {
                echo "<li class='prev'><a href='order_history.php?page=" . $page - 1 . "'>Prev</a></li>";
            }
            if ($page > 3 && isset($_GET['search'])) {
                echo "<li class='start'><a href='order_history.php?search=" . $_GET['search'] . "&page=1'>1</a></li>
                    <li class='dots'>...</li>";
            }
            else if ($page > 3) {
                echo "<li class='start'><a href='order_history.php?page=1'>1</a></li>
                    <li class='dots'>...</li>";
            }
            if ($page - 2 > 0 && isset($_GET['search'])) {
                echo "<li class='page'><a href='order_history.php?search=" . $_GET['search'] . "&page=" . $page - 2 . "'>" . $page - 2 . "</a></li>";
            }
            else if ($page - 2 > 0) {
                echo "<li class='page'><a href='order_history.php?page=" . $page - 2 . "'>" . $page - 2 . "</a></li>";
            }
            if ($page - 1 > 0 && isset($_GET['search'])) {
                echo "<li class='page'><a href='order_history.php?search=" . $_GET['search'] . "&page=" . $page - 1 . "'>" . $page - 1 . "</a></li>";
            }
            else if ($page - 1 > 0) {
                echo "<li class='page'><a href='order_history.php?page=" . $page - 1 . "'>" . $page - 1 . "</a></li>";
            }
            if (isset($_GET['search'])) {
                echo "<li class='currentpage'><a href='order_history.php?search=" . $_GET['search'] . "&page=" . $page . "'>" . $page . "</a></li>";
            }
            else {
                echo "<li class='currentpage'><a href='order_history.php?page=" . $page . "'>" . $page . "</a></li>";
            }
            if ($page + 1 < ceil($count_order / $num_result_on_page) + 1 && isset($_GET['search'])) {
                echo "<li class='page'><a href='order_history.php?search=" . $_GET['search'] . "&page=" . $page + 1 . "'>" . $page + 1 . "</a></li>";
            }
            else if ($page + 1 < ceil($count_order / $num_result_on_page) + 1) {
                echo "<li class='page'><a href='order_history.php?page=" . $page + 1 . "'>" . $page + 1 . "</a></li>";
            }
            if ($page + 2 < ceil($count_order / $num_result_on_page) + 1 && isset($_GET['search'])) {
                echo "<li class='page'><a href='order_history.php?search=" . $_GET['search'] . "&page=" . $page + 2 . "'>" . $page + 2 . "</a></li>";
            }
            else if ($page + 2 < ceil($count_order / $num_result_on_page) + 1) {
                echo "<li class='page'><a href='order_history.php?page=" . $page + 2 . "'>" . $page + 2 . "</a></li>";
            }
            if ($page < ceil($count_order / $num_result_on_page) - 2 && isset($_GET['search'])) {
                echo "<li class='dots'>...</li>
                    <li class='end'><a href='order_history.php?search=" . $_GET['search'] . "&page=" . ceil($count_order / $num_result_on_page) . "'>" . ceil($count_order / $num_result_on_page) . "</a></li>";
            }
            else if ($page < ceil($count_order / $num_result_on_page) - 2) {
                echo "<li class='dots'>...</li>
                    <li class='end'><a href='order_history.php?page=" . ceil($count_order / $num_result_on_page) . "'>" . ceil($count_order / $num_result_on_page) . "</a></li>";
            }
            if ($page < ceil($count_order / $num_result_on_page) && isset($_GET['search'])) {
                echo "<li class='next'><a href='order_history.php?search=" . $_GET['search'] . "&page=" . $page + 1 . "'>Next</a></li>";
            }
            else if ($page < ceil($count_order / $num_result_on_page)) {
                echo "<li class='next'><a href='order_history.php?page=" . $page + 1 . "'>Next</a></li>";
            }
            echo "</ul>";
        }
    }
}

