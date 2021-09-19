<header>
    <a class="logo" href="/">MyShop</a>
    <nav class="header-nav">
        <ul>
            <?php 
                require_once "include/header.inc.php";
                # Header for logged in user
                if (isset($_SESSION['id'])) {
                    echo "<li><p>Hello, ";
                    # If customer name is not NULL in db show email adress, otherwise show name
                    if ($_SESSION['name'] == null) {
                        echo $_SESSION['email'];
                    }
                    else {
                        echo $_SESSION['name'] ;
                    }
                    echo "\n (<a href='include/logout.inc.php'>logout</a>)\n";
                    echo "<a href='order_history.php'>" . $count_unpaid_order . " unpaid order</a></p></li>"; 
                    echo '<li>|</li>
                        <li> <a href="topup_balance.php">Prepaid Balance</a> </li>
                        <li>|</li> 
                        <li> <a href="product_page.php">Product Page</a> </li>
                        ';
                }
                # Header for non logged in user
                else {
                    echo '<li> <a href="login.php">Login</a> </li>
                        <li>|</li>
                        <li> <a href="register.php">Register</a> </li>
                        ';
                }
            ?> 
        </ul>
    </nav>
</header>