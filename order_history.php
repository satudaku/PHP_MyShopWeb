<?php
	require_once 'include/logged_session.inc.php';
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>MyShop - Order History</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>

	<div class="container">

		<!-- Navigation Header -->
		<?php
			include_once 'header.php';
		?>

		<!-- Main Content -->
		<section class="main-content">
			<div class="input-form">
				<h1>Order History</h1>
				<div class="search-container">
					<form class="search-bar" action="order_history.php" method="get">
						<input class="input-text" type="text" name="search" <?php 
							if (isset($_GET['search'])) { echo 'value="' . $_GET['search']; } 
							else {echo 'placeholder="Search by order no.';}?>">
						<button class="input-button" type="submit">Search</button>
					</form>
				</div>
				<?php
					require_once 'include/order_history.inc.php'
				?>
			</div>
		</section>

		<!-- Footer -->
		<?php
			include_once 'footer.php';
		?>

	</div>

</body>

</html>