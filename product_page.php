<?php
	include_once 'include/logged_session.inc.php';
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>MyShop - Product Page</title>
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
				<h1>Product Page</h1>
				<form action="include/product_page.inc.php" method="post" class="form">
					<input class="input-text product" type="text" name="product" placeholder="Product">
					<input class="input-text product" type="text" name="shipping_address" placeholder="Shipping address">
					<input class="input-text" type="text" name="product_price" placeholder="Price">
                    <?php
						if(isset($_GET["error"])) {
							if ($_GET["error"] == "empty_input") {
								echo "<p class='error'>*All fields are required!</p>";
							}
							else if ($_GET["error"] == "invalid_price") {
								echo "<p class='error'>Only numbers/integers!</p>";
							}
							else if ($_GET["error"] == "stmt_failed") {
								echo "<p class='error'>*Something went wrong! Please try again.</p>";
							}
						}
					?>
					<input class="input-button" type="submit" value="Submit" name="submit">
				</form>
			</div>
		</section>

		<!-- Footer -->
		<?php
			include_once 'footer.php';
		?>

	</div>
</body>

</html>