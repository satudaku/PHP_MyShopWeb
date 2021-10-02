<?php
	require_once 'include/non_logged_session.inc.php';
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>MyShop - Login</title>
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
				<h1>Login</h1>
				<form action="include/login.inc.php" method="post" class="form">
					<input class="input-text" type="email" name="email" placeholder="Email">
					<input class="input-text" type="password" name="password" minlenght="6" placeholder="Password">
					<?php
					# Login form error message
						if(isset($_GET["error"])) {
							if ($_GET["error"] == "empty_input") {
								echo "<p class='error'>*Must include email and password!</p>";
							}
							else if ($_GET["error"] == "invalid_credetial") {
								echo "<p class='error'>*Email adress and password did not match!</p>";
							}
							else if ($_GET["error"] == "stmt_failed") {
								echo "<p class='error'>*Something went wrong! Please try again.</p>";
							}
						}
					?>
					<input class="input-button" type="submit" value="Login" name="submit">
				</form>
			</div>
			<a class="link" href="register.php">Register</a>
		</section>

		<!-- Footer -->
		<?php
			include_once 'footer.php';
		?>

	</div>

</body>
						
<?php
	# If user access login required page without session, alert to login first
	if (isset($_GET["user"])) {
		if ($_GET["user"] == "login_first") {
			?>
				<script type = 'text/javascript'>
					alert('Please login first!')
				</script> 
			<?php
		}
	}
?>

</html>