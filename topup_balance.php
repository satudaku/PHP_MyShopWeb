<?php
	include_once 'include/logged_session.inc.php';
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>MyShop - Prepaid Balance</title>
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
				<h1>Prepaid Balance</h1>
				<form action="include/topup_balance.inc.php" method="post" class="form">
					<input class="input-text mobile-no fixed" value="081">
					<input class="input-text mobile-no" type="tel" name="mobile_no" placeholder="Mobile Number">
					<select class="input-select" name="balance_value">
						<option value="" selected disabled hidden>Choose Value</option>
						<option value="10000">10.000</option>
						<option value="50000">50.000</option>
						<option value="100000">100.000</option>
					</select>
					<?php
						if(isset($_GET["error"])) {
							if ($_GET["error"] == "empty_input") {
								echo "<p class='error'>*All fields are required!</p>";
							}
							else if ($_GET["error"] == "invalid_mobile_no") {
								echo "<p class='error'>*Mobile number can only be 7 to 12 digits of numbers!</p>";
							}
							else if ($_GET["error"] == "stmt_failed") {
								echo "<p class='error'>*Something went wrong! Please try again.</p>";
							}
							else if ($_GET["error"] == "failed") {
								echo "<p class='error'>*If paid within 9AM to 5PM, success rate is 90% (otherwise 40%).</p>";
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

<?php
	# If user access no login required page with session, alert to logout first
	if (isset($_GET["user"])) {
		if ($_GET["user"] == "logout_first") {
			?>
				<script type = 'text/javascript'>
					alert('Please logout first!')
				</script> 
			<?php
		}
	}
?>

</html>