<!-- Php script for page header -->

<?php

require_once 'dbh.inc.php';
require_once 'function.inc.php';

if (isset($_SESSION["id"])) {
    $count_unpaid_order = count_unpaid_order($conn, $_SESSION['id']);
}