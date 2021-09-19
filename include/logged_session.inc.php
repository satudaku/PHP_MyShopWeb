<!-- Restrict non logged-in users to access the page --> 
<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location:Login.php");
    exit();
}