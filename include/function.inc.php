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

# Validation for product price input
function invalid_integer($product_price) {
    if (!filter_var($product_price, FILTER_VALIDATE_INT)) {
        return false;
    }
    else {
        return $product_price;
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

# Find data row from table order_status 
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

        # Return row which has the same given order_status_id 
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

# Find data row from table order_topup
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

        # Return row which has the same given order_topup_id
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

# Find data row from table order_product
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

        # Return row which has the same given order_topup_id
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

    echo "order id: " . $order_id;
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
        $order_paid_time = date('Y-m-d H:i:s');     # Current time
        if ($order_id_exist["payment_due_time"] <= $order_paid_time) {
            header("location: ../pay_order.php?error=payment_overtime");
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

            # Update order_status to paid
            $sql = "UPDATE order_status SET order_status = ? WHERE order_id = ?;";
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header ("location: ../pay_order.php?error=stmt_failed");
                exit();
            }
            else {
                $order_status = "paid";
                # Bind parameters to the place holder
                mysqli_stmt_bind_param($stmt, "si", $order_status, $order_id);
                # Run parameters inside database
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header("location: ../order_history.php");
                exit();
            }       
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
        
        # Return result, false if order paid or cancelled or failed
        $result = false;

        if ($row = mysqli_fetch_assoc($result_data)) {
            # Check order status
            $status_array = array("paid", "cancelled", "failed");
            if (in_array($row["order_status"], $status_array)) {
                mysqli_stmt_close($stmt);
                return $result;
            }
            if ($row["order_status"] == "unpaid") {
                # Compare payment due time with current time
                $current_time = date('Y-m-d H:i:s'); 
                if ($row["payment_due_time"] > $current_time) {
                    mysqli_stmt_close($stmt);
                    $result = true;
                    return $result;
                }
                else {
                    # Update order status to cancelled
                    $sql = "UPDATE order_status SET order_status = ? WHERE order_id = ?;";
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header ("location: ../pay_order.php?error=stmt_failed");
                        exit();
                    }
                    else {
                        $order_status = "cancelled";
                        # Bind parameters to the place holder
                        mysqli_stmt_bind_param($stmt, "si", $order_status, $order_id);
                        # Run parameters inside database
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        return $result;
                    }
                }
            }
        }
        else {
            mysqli_stmt_close($stmt);
            return $result;
        }
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
        if (!is_array($row)) {
            return $unpaid_order;
            exit();
        }
        else {
            foreach ($data as $row) {
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

# Get data row from table order_status 
function get_order($conn, $id) {
    $sql = "SELECT * FROM order_status WHERE customer_id = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header ("location: ../topup_order.php?error=stmt_failed3");
        exit();
    }
    else {
        # Bind  parameters to the placeholder
        mysqli_stmt_bind_param($stmt, "i", $id);
        # Run parameters inside database
        mysqli_stmt_execute($stmt);
        $result_data = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        while ($row = mysqli_fetch_assoc($result_data)) {
            $data[] = $row;
        }
        return $data;
        exit();
    }
}
