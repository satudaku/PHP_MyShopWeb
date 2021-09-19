<!-- Establish database connection myshop db-->
<?php
# Database variables
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "myshop";

# Create connection
$conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);

# Error handling if connection failed
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}