<?php
	require_once 'include/non_logged_session.inc.php';
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>MyShop - Register</title>
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
				<h1>Register</h1>
				<form action="include/register.inc.php" method="post" class="form">
					<input class="input-text" type="text" name="name" placeholder="Name">
					<input class="input-text" type="email" name="email" placeholder="Email">
					<input class="input-text" type="password" name="password" minlenght="6" placeholder="Password">
					<?php
					# Register form error handling
						if(isset($_GET["error"])) {
							if ($_GET["error"] == "empty_input") {
								echo "<p>*Must include email and password!</p>";
							}
							else if ($_GET["error"] == "invalid_email") {
								echo "<p>*Invalid email adress!</p>";
							}
							else if ($_GET["error"] == "email_already_exist") {
								echo "<p>*The email adress already exist, try to login with it!</p>";
							}
							else if ($_GET["error"] == "invalid_password") {
								echo "<p>*Password must be between 6 to 18 charachters!</p>";
							}
							else if ($_GET["error"] == "stmt_failed") {
								echo "<p>*Something went wrong! Please try again.</p>";
							}
						}
					?>
					<input class="input-button" type="submit" value="Register" name="submit">
				</form>
			</div>
			<a class="link" href="login.php">Login</a>
		</section>
		<!-- Footer -->
		<?php
			include_once 'footer.php';
		?>
	</div>
</body>

</html>