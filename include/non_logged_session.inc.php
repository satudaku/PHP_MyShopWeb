<!-- Restrict sessioned users to access certain pages --> 
<?php
session_start();
if (isset($_SESSION['id'])) {
    header("location:Topup_balance.php");
    exit();
}