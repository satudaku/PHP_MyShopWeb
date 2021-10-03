<!-- Php functions used in the MyShop web pages -->

<?php

######################################################
################## R E G I S T E R ###################
######################################################

# If user register with empty form in email or password or both error handling
function empty_input_register($email, $password) {
    $result = true;
    if (empty($email) || empty($password)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

# If user register with wrong email format error handling
function invalid_email($email) {
    $result = true;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

# If user register with email already exists in db error handling
function email_exist($conn, $email) {
    $sql = "SELECT * FROM customer WHERE customer_email = ?;";
    # Prepared statements to prevent against SQL injections
    
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../register.php?error=stmt_failed");
        exit();
    }
    else {
        #bind  parameters to the placeholder
        mysqli_stmt_bind_param($stmt, "s", $email);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        $result_data = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result_data)) {
            mysqli_stmt_close($stmt);
            return $row;
        }
        else {
            mysqli_stmt_close($stmt);
            $result = false;
            return $result;
        }
    }
}

# If user input password that doesn't meet the creteria
function invalid_password($password) {
    $result = true;
    $min_password_lenght = 6;
    $max_password_lenght = 18;
    if (!strlen(trim($password)) >= $min_password_lenght & !strlen(trim($password)) <= $max_password_lenght) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

# Register customer into the db myshop
function create_customer($conn, $name, $email, $password) {
    $sql = "INSERT INTO customer (customer_name, customer_email, customer_password) VALUE (?, ?, ?);";
    # Prepared statements to prevent against SQL injections
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../register.php?error=stmt_failed");
        exit();
    }
    else {
        # Hashing the user password before storing to the db
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        # Bind the parameters into the placeholder
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed_password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    
        echo "You have been successfully register!";
        header ("location: ../login.php");
    }

}

######################################################
##################### L O G I N ######################
######################################################

# If user submit empty form
function empty_input_login($email, $password) {
    $result = true;
    if (empty($email) || empty($password)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

# Login authentification of user's credentials
function login_customer($conn, $email, $password) {

    # Check wether or not the customer input exist in the db
    $customer_id_exist = email_exist($conn, $email);

    # If not exist show error message
    if ($customer_id_exist === false) {
        header("location: ../login.php?error=invalid_credetial");
        exit();
    }

    $customer_password = $customer_id_exist["customer_password"];
    $check_password = password_verify($password, $customer_password);

    if ($check_password === false) {
        header("location: ../login.php?error=invalid_credetial");
        exit();
    }
    else if ($check_password === true) {
        session_start();
        $_SESSION["id"] = $customer_id_exist["customer_id"];
        $_SESSION["name"] = $customer_id_exist["customer_name"];
        $_SESSION["email"] = $customer_id_exist["customer_email"];
        header("location: ../topup_balance.php");
        exit();
    }
    
}

######################################################
############ T O P U P - B A L A N C E ###############
######################################################

# If user submit empty form
function empty_input_topup($mobile_no, $balance_value) {
    $result = true;
    if (empty($mobile_no) || empty($balance_value)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

# Validation for mobile phone number
function invalid_mobile_no($mobile_no) {
    $result = false;
    # Allow +, -, and .  number
    $filtered_mobile_no = filter_var($mobile_no, FILTER_SANITIZE_NUMBER_INT);
    # Remove "-", "(", ")", ".", "+" from number
    $check_number = str_replace(["-", "(", ")", ".", "+"], "", $filtered_mobile_no);
    # Add prefixed number "081"
    $prefix_number = "081";
    $mobile_no = $prefix_number . $check_number;
    # Check the lenght of the number
    $min_digit = 7;
    $max_digit = 12;
    if ($min_digit <= strlen($mobile_no) AND strlen($mobile_no) <= $max_digit) {
        return $mobile_no;
    } 
    else {
        return $result;
    }
}

# Inserting topup balance data into db
function topup_balance($conn, $id, $mobile_no, $balance_value) {

    #check wether the mobile number is valid
    $mobile_no = invalid_mobile_no($mobile_no);
    if ($mobile_no === false) {
        header("location: ../topup_balance.php?error=invalid_mobile_no");
        die();
    }
    
    # Add 5% on top of value
    $percentage = (5 / 100) * $balance_value;
    $balance_value = $balance_value + $percentage; 
    # Insert into order_topup table for mobile number and balance value
    $sql = "INSERT INTO order_topup (mobile_no, balance_value) VALUE (?, ?);";
    # Using prepared statement 
    $stmt = mysqli_stmt_init($conn);
    # If failed to initialize error handling 
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../topup_balance.php?error=stmt_failed1");
        exit();
    }
    # Otherwise insert data into the table
    else {
        mysqli_stmt_bind_param($stmt, "si", $mobile_no, $balance_value);
        mysqli_stmt_execute($stmt);
    }

    # Some attributes to insert into order_status table
    $order_placed_time = date('Y-m-d H:i:s');   # Current time
    $payment_due_time = date('Y-m-d H:i:s', strtotime($order_placed_time. ' + 5 minutes'));   # Payment due time
    $order_status = "unpaid";   # The order status
    $order_topup_id =  mysqli_stmt_insert_id($stmt); # Fetch order_top_id from last inserted query

    // echo "order placed time: " . $order_placed_time . "\n" . "payment due: " . $payment_due_time . "\n" . "status: " . $order_status;
    // die();
    # Insert data into order_status table
    $sql = "INSERT INTO order_status (order_placed_time, payment_due_time, order_status, customer_id, order_topup_id) 
        VALUE (?, ?, ?, ?, ?);";
    # using prepared statement, if failed error handling
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../topup_balance.php?error=stmt_failed2");
        exit();
    }
    # Otherwise insert data into the table
    else {
        mysqli_stmt_bind_param($stmt, "sssii", $order_placed_time, $payment_due_time, $order_status, $id, $order_topup_id);
        mysqli_stmt_execute($stmt);
        $_SESSION["order_id"] = mysqli_stmt_insert_id($stmt);
        mysqli_stmt_close($stmt);
        header("location: ../success_create_order.php");
        exit();
    }
}


######################################################
############# P R O D U C T - P A G E ################
######################################################

# If user submit empty form
function empty_input_product($product, $shipping_address, $product_price) {
    $result = true;
    if (empty($product) || empty($shipping_address) || empty($product_price)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

# Input validation for interger
function invalid_integer($int) {
    if (!filter_var($int, FILTER_VALIDATE_INT)) {
        return false;
    }
    else {
        return $int;
    }
}

# Generate random string
function random_string($string_length) {
    # Characters used to generate random string
    $string_char = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    # Shuffle the $string_char and returns substring of given length
    return substr(str_shuffle($string_char), 0, $string_length);
}

# Generate shipping code
function generate_shipping_code($conn) {
    $sql = "SELECT shipping_code FROM order_status;";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $string_length = 8;
    $shipping_code = random_string($string_length);
    while($row["shipping_code"] == $shipping_code) {
        $shipping_code = random_string($string_length);
    }
    return $shipping_code;
}

# Inserting topup balance data into db
function order_product($conn, $id, $product, $shipping_address, $product_price) {

    #check wether the mobile number is valid
    $product_price = invalid_integer($product_price);
    if ($product_price === false) {
        header("location: ../product_page.php?error=invalid_price");
        die();
    }
    # Add shipping cost on top of price
    $shipping_cost = 10000;
    $product_price = $product_price + $shipping_cost;

    # Insert into order_topup table for mobile number and balance value
    $sql = "INSERT INTO order_product (product, shipping_address, product_price) VALUE (?, ?, ?);";
    # Using prepared statement 
    $stmt = mysqli_stmt_init($conn);
    # If failed to initialize error handling 
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../product_balance.php?error=stmt_failed1");
        exit();
    }
    # Otherwise insert data into the table
    else {
        mysqli_stmt_bind_param($stmt, "ssi", $product, $shipping_address, $product_price);
        mysqli_stmt_execute($stmt);
    }

    # Some variables to insert into order_status table
    $order_placed_time = date('Y-m-d H:i:s');   # Current time
    $payment_due_time = date('Y-m-d H:i:s', strtotime($order_placed_time. ' + 5 minutes'));   # Payment due time
    $order_status = "unpaid";   # The order status
    $shipping_code = generate_shipping_code($conn); # Generate shipping code 
    $order_product_id =  mysqli_stmt_insert_id($stmt); # Fetch order_top_id from last inserted query

    # Insert data into order_status table
    $sql = "INSERT INTO order_status (order_placed_time, payment_due_time, order_status, shipping_code, customer_id, order_product_id) 
        VALUE (?, ?, ?, ?, ?, ?);";
    # Using prepared statement, if failed error handling
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../product_page.php?error=stmt_failed2");
        exit();
    }
    # Otherwise insert data into the table
    else {
        mysqli_stmt_bind_param($stmt, "ssssii", $order_placed_time, $payment_due_time, $order_status, $shipping_code, $id, $order_product_id);
        mysqli_stmt_execute($stmt);
        $_SESSION["order_id"] = mysqli_stmt_insert_id($stmt);
        mysqli_stmt_close($stmt);
        header("location: ../success_create_order.php");
        exit();
    }
}


######################################################
############### Success Create Order #################
######################################################

# Find data record from table order_status 
function find_status_order($conn, $order_id) {
    $sql = "SELECT * FROM order_status WHERE order_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../topup_order.php?error=stmt_failed3");
        exit();
    }
    else {
        # Bind  parameters to the placeholder
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        $result_data = mysqli_stmt_get_result($stmt);

        # Return record which has the same given order_status_id 
        if ($row = mysqli_fetch_assoc($result_data)) {
            mysqli_stmt_close($stmt);
            return $row;
        }
        else {
            mysqli_stmt_close($stmt);
            $result = false;
            return $result;
        }
    }
}

# Find data record from table order_topup
function find_topup_order($conn, $order_topup_id) {
    $sql = "SELECT * FROM order_topup WHERE order_topup_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../topup_order.php?error=stmt_failed");
        exit();
    }
    else {
        #bind  parameters to the placeholder
        mysqli_stmt_bind_param($stmt, "i", $order_topup_id);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        $result_data = mysqli_stmt_get_result($stmt);

        # Return record which has the same given order_topup_id
        if ($row = mysqli_fetch_assoc($result_data)) {
            mysqli_stmt_close($stmt);
            return $row;
        }
        else {
            mysqli_stmt_close($stmt);
            $result = false;
            return $result;
        }
    }
}

# Find data record from table order_product
function find_product_order($conn, $order_product_id) {
    $sql = "SELECT * FROM order_product WHERE order_product_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../topup_order.php?error=stmt_failed");
        exit();
    }
    else {
        #bind  parameters to the placeholder
        mysqli_stmt_bind_param($stmt, "i", $order_product_id);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        $result_data = mysqli_stmt_get_result($stmt);

        # Return record which has the same given order_topup_id
        if ($row = mysqli_fetch_assoc($result_data)) {
            mysqli_stmt_close($stmt);
            return $row;
        }
        else {
            mysqli_stmt_close($stmt);
            $result = false;
            return $result;
        }
    }
}


######################################################
################# P A Y - O R D E R ##################
######################################################

# If user submit empty form
function empty_input_pay($order_id) {
    $result = true;
    if (empty($order_id)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

# Check wether order_id input in database
function order_id_exist($conn, $order_id) {
    
    $sql = "SELECT * FROM order_status WHERE order_id = ?;";
    # Prepared statements to prevent against SQL injections
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../{$_SERVER['PHP_SELF']}?error=stmt_failed");
        exit();
    }
    else {
        #bind  parameters to the placeholder
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        $result_data = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result_data)) {
            mysqli_stmt_close($stmt);
            return $row;
        }
        else {
            mysqli_stmt_close($stmt);
            $result = false;
            return $result;
        }
    }
}

# Function to pay order placed
function pay_order($conn, $id, $order_id) {
    
    # Check wether the order_id input is valid
    $order_id = invalid_integer($order_id);
    if ($order_id === false) {
        header("location: ../pay_order.php?error=invalid_order_id");
        die();
    }

    # Check wether order_id exist and has match the customer id
    $order_id_exist = order_id_exist($conn, $order_id);
    if ($order_id_exist === false && $order_id_exist["customer_id"] !== $id) {
        header("location: ../pay_order.php?error=invalid_order_id");
        exit();
    }
    else {
        # Error handler if order is already paid or cancelled
        if ($order_id_exist["order_status"] == "paid") {
            header("location: ../pay_order.php?error=already_paid");
            exit();
        }
        else if ($order_id_exist["order_status"] == "cancelled"){
            header("location: ../pay_order.php?error=order_cancelled");
            exit();
        }
        else if ($order_id_exist["order_status"] == "failed"){
            header("location: ../pay_order.php?error=order_failed");
            exit();
        }
        
        # Check if payment made before due time
        $payment_overdue = check_payment_due($conn, $order_id, $order_id_exist["payment_due_time"]);
        if ($payment_overdue === true) {
            header("location: ". $_SERVER['HTTP_REFERER'] . "&error=payment_overtime");
            exit();
        }
        else {

            # Update order_paid_time in order_status table to current time
            $sql = "UPDATE order_status SET order_paid_time = ? WHERE order_id = ?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header ("location: ../{$_SERVER['REQUEST_URI']}?error=stmt_failed22");
                exit();
            }
            else {
                mysqli_stmt_bind_param($stmt, "si", $order_paid_time, $order_id);
                # Run parameters inside database
                mysqli_stmt_execute($stmt);
            }

            # Topup balance transaction have a chance to fail
            if ($order_id_exist["order_topup_id"] != null) {
                $transaction_status = transaction_failed($order_paid_time);
                # If transaction failed
                if ($transaction_status === false) {
                    # Update order_status to paid
                    $updated_order_status = "failed";
                }
                # Else transaction goes tru
                else {
                    # Update order_status to paid
                    $updated_order_status = "paid"; 
                }
            }

            change_order_status($conn, $order_id, $updated_order_status);
            # If transaction failed, return user to topup balance page
            if ($transaction_status = "failed") {
                ?>
                    <script type = 'text/javascript'>
                    alert('Transaction Failed please try again!')
                    window.location.href = '../topup_balance.php?error=failed'
                    </script>
                <?php
                exit();
            }
            # Else transaction success, return user to order history page
            else {
                ?>
                    <script type = 'text/javascript'>
                    alert('Order paid successfully!')
                    window.location.href = '../order_history.php'
                    </script>
                <?php
                exit();
            }
        }
    }
}

# Check if payment overdue
function check_payment_due($conn, $order_id, $payment_due_time) {
    $result = true;
    $order_paid_time = date('Y-m-d H:i:s');     # Current time
    # If payment due time overdue
    if ($payment_due_time < $order_paid_time) {
        $updated_order_status = "cancelled";    # Changed variable
        # Update order_status to cancelled
        change_order_status($conn, $order_id, $updated_order_status);
        return $result;
    }
    else {
        $result = false;
        return $result;
    }
}

# Update order_status record to updated_order_status in order_status table
function change_order_status($conn, $order_id, $updated_order_status) {
    $sql = "UPDATE order_status SET order_status = ? WHERE order_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../{$_SERVER['REQUEST_URI']}?error=stmt_failed22");
        exit();
    }
    else {
        mysqli_stmt_bind_param($stmt, "si", $updated_order_status, $order_id);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

# Topup balance transaction have a chance to fail
# If paid within 9AM to 5PM, success rate is 90% (otherwise 40%)
function transaction_failed($order_paid_time) {
    $chance = mt_rand(0, 99) / 100;
    $open_time = new DateTime("09:00:00");
    $close_time = new DateTime("17:00:00");
    if ($open_time < $order_paid_time && $order_paid_time > $close_time) {
        if ($chance < 1/10) {
            return false;
        }
        else {
            return true;
        }
    }
    else {
        if ($chance < 6/10) {
            return false;
        }
        else {
            return true;
        }
    }
}

######################################################
############## C H E C K - P A Y M E N T #############
######################################################

# Check status in order_status table
function check_status($conn, $order_id) {
    $sql = "SELECT * FROM order_status WHERE order_id = ?;";
    # Prepared statements to prevent against SQL injections
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../pay_order.php?error=stmt_failed");
        exit();
    }
    else {
        #bind  parameters to the placeholder
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        $result_data = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        
        # Return result, false if order paid or cancelled or failed
        $result = false;

        if ($row = mysqli_fetch_assoc($result_data)) {
            # Check order status
            $status_array = array("paid", "cancelled", "failed");
            if (in_array($row["order_status"], $status_array)) {
                return $result;
            }
            if ($row["order_status"] == "unpaid") {
                $payment_due_time = $row["payment_due_time"];
                # Check if payment already overdue
                $payment_overdue = check_payment_due($conn, $order_id, $payment_due_time);
                if ($payment_overdue === true) {
                    return $result;
                }
                else {
                    $result = true;
                    return $result;
                }
            }
        }
        return $result;
    }
}

# Count unpaid order with given customer id
function count_unpaid_order($conn, $id) {
    # sql query
    $sql = "SELECT * FROM order_status WHERE customer_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../pay_order.php?error=stmt_failed");
        exit();
    }
    else {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result_data = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        $unpaid_order = 0;
        while ($row = mysqli_fetch_assoc($result_data)) {
            $data[] = $row;
        }
        if (!isset($data)) {
            return $unpaid_order;
            exit();
        }
        else {
            foreach ($data as $row) {
                # for each check status return true, add count to unpaid_order
                if (check_status($conn, $row["order_id"]) === true) {
                    $unpaid_order++;
                }   
            }
            return $unpaid_order;
        }
    }
}


######################################################
############## O R D E R - H I S T O R Y #############
######################################################

# Get data record from table order_status 
function get_order($conn, $id, $page, $num_result_on_page) {
    $sql = "SELECT * FROM order_status WHERE customer_id = ? ORDER BY order_placed_time DESC LIMIT ?,?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../order_history.php?error=stmt_failed3");
        exit();
    }
    else {
        # Calculate the page to get the results we need from our table.
	    $calculate_page = ($page - 1) * $num_result_on_page;
        # Bind  parameters to the placeholder
        mysqli_stmt_bind_param($stmt, "iii", $id, $calculate_page, $num_result_on_page);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        $result_data = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        while ($row = mysqli_fetch_assoc($result_data)) {
            $data[] = $row;
        }
        return $data;
    }
}

# Get the total number of order records from given id
function count_order($conn, $id) {
    $sql = "SELECT * FROM order_status WHERE customer_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../order_history.php?error=stmt_failed");
        exit();
    }
    else {
        # Bind  parameters to the placeholder
        mysqli_stmt_bind_param($stmt, "i", $id);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        $result_data = mysqli_stmt_get_result($stmt);
        $count_order = mysqli_num_rows($result_data);
        mysqli_stmt_close($stmt);
        return $count_order;
    }
}

# Search bar for order history using order ID
function search_order($conn, $id, $keyword, $page, $num_result_on_page) {
    $sql = "SELECT * FROM order_status WHERE customer_id = ? AND order_id LIKE ? ORDER BY order_placed_time DESC LIMIT ?,?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../order_history.php?error=stmt_failed");
        exit();
    }
    else {
        # Calculate the page to get the results we need from our table.
	    $calculate_page = ($page - 1) * $num_result_on_page;
        # Bind  parameters to the placeholder
        mysqli_stmt_bind_param($stmt, "isii", $id, $keyword, $calculate_page, $num_result_on_page);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        $result_data = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        # Fetch search data into array
        while ($row = mysqli_fetch_assoc($result_data)) {
            $data[] = $row;
        }
        return $data;
    }
}

# Count searched records found
function count_search($conn, $id, $keyword) {
    $sql = "SELECT * FROM order_status WHERE customer_id = ? AND order_id LIKE ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../order_history.php?error=stmt_failed");
        exit();
    }
    else {
        # Bind  parameters to the placeholder
        mysqli_stmt_bind_param($stmt, "is", $id, $keyword);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        # Get the total number of records
        $count_order = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        return $count_order;
    }
}

# Check search keyword contain other than numbers
function invalid_search_order($keyword) {
    if (!ctype_digit($keyword)) {
        return false;
    }
    else {
        return $keyword;
    }
}