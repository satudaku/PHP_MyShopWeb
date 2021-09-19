<!-- Php script for pay_order page -->
<?php
session_start();

if (!isset($_SESSION["id"])){
    ?>
    <script type = 'text/javascript'>
    alert('Please login first!')
    window.location.href = '../login.php'
    </script>
    <?php
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