<?php
	include_once 'include/logged_session.inc.php';
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>MyShop - Success Create Order</title>
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
		<?php
			require_once 'include/success_create_order.inc.php';
		?>
		<section class="main-content">
			<div class="input-form">
				<h1>Success</h1>
                </br>
                </br>
				<table class="table-summary">
					<tr>
						<td><p style="text-align:right">Order no:</p></td>
						<td><?php echo $_SESSION["order_id"]; ?></td>
					</tr>
					<tr>
						<td><p style="text-align:right">Total:</p></td>
						<td>RP. <?php 	if ($order_topup_id != false) {
									  		echo $balance_value;
										}
										else if ($order_product_id != false) {
											echo $product_price;
										}
								?>
						</td>
					</tr>
					<?php
						if ($order_topup_id != false) {
							?>
							<tr>
								<td colspan="2"><p style="text-align:center">Prepaid balance that costs <?php echo $balance_value?> for mobile number: <?php echo $mobile_no ?></p></td>
							</tr>
							<tr>
								<td colspan="2"><p class="warning">Pay before: <?php echo $payment_due_time;?></p> </td>
							</tr>
							<?php
						}
						else if ($order_product_id != false) {
							?>
							<tr>
								<td colspan="2"><p style="text-align:center"><?php echo $product?> that costs Rp.<?php echo $product_price?> will be shipped to: <?php echo $shipping_address?></p></td>
							</tr>
							<tr>
								<td colspan="2"><p class="warning">Pay before: <?php echo $payment_due_time;?></p> </td>
							</tr>
							<?php
						}
					?>
				</table>
				<form class="form" href="success_create_order.inc.php" method="POST">
					<input class="input-button" type="submit" value="Pay now" name="submit">
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