<?php
	require_once 'include/logged_session.inc.php';
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>MyShop - Pay Order</title>
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
				<h1>Pay Your Order</h1>
				<form action="include/pay_order.inc.php" method="post" class="form">
					<input class="input-text" type="text" name="order_id"
						<?php
							# Order id input form value
							# Either get it from user input, link, or SESSION
							if (isset($_GET["order_id"])) {echo 'value="' . $_GET["order_id"];} 
							else if (isset($_SESSION["order_id"])) {echo 'value="' . $_SESSION["order_id"];}
							else {echo 'placeholder="Order no. here';}
						?>
					">
					<?php
					# Login form error message
						if(isset($_GET["error"])) {
							if ($_GET["error"] == "empty_input") {
								echo "<p class='error'>*Required field!</p>";
							}
							else if ($_GET["error"] == "invalid_order_id") {
								echo "<p class='error'>*Invalid order no.!</p>";
							}
							else if ($_GET["error"] == "stmt_failed") {
								echo "<p class='error'>*Something went wrong! Please try again.</p>";
							}
							else if ($_GET["error"] == "already_paid") {
								echo "<p class='error'>*Order already been paid!</p>";
							}
							else if ($_GET["error"] == "order_cancelled") {
								echo "<p class='error'>*Order cancelled! Please order again.</p>";
							}
							else if ($_GET["error"] == "payment_overtime") {
								echo "<p class='error'>*Payment overdue! order cancelled.</p>";
							}
						}
					?>
					<input class="input-button" type="submit" value="Pay" name="submit">
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