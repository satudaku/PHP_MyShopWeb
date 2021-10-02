<header>
    <div class="header-row">
        <div class="header-column">
            <a class="header-logo" href="/">MyShop</a>
        </div>

        <div class="header-column">
            <?php 
                require_once "include/header.inc.php";
                # Header for logged in user
                if (isset($_SESSION['id'])) {
                    echo "<p class='header-user'>Hello, ";
                    # If customer name is not NULL in db show email adress, otherwise show name
                    if ($_SESSION['name'] == null) {
                        echo $_SESSION['email'];
                    }
                    else {
                        echo $_SESSION['name'] ;
                    }
                    echo " <a class='user-logout' href='include/logout.inc.php'>(logout)</a></p>";

                    echo "<nav class='header-nav'>
                    <ul>
                        <li> <a href='order_history.php'>" . $count_unpaid_order . " unpaid order</a> </li>
                        <li><p class='divider'>|</p></li>
                        <li> <a href='topup_balance.php'>Prepaid Balance</a> </li>
                        <li><p class='divider'>|</p></li> 
                        <li> <a href='product_page.php'>Product Page</a> </li>
                    </ul>
                    </nav>";
                }
                # Header for non logged in user
                else {
                    echo "<nav class='header-nav'>
                    <ul>
                        <li> <a href='login.php'>Login</a> </li>
                        <li><p class='divider'>|</p></li>
                        <li> <a href='register.php'>Register</a> </li>
                    </ul>
                    </nav>";
                }
            ?> 
        </div>
    </div>
</header>