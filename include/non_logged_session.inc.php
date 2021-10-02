<!-- Restrict sessioned users to access certain pages --> 
<?php
session_start();
if (isset($_SESSION['id'])) {
    header("location:topup_balance.php?user=logout_first");
    exit();
}