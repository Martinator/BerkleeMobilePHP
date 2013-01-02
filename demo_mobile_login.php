<? require_once('_lib/_db.php') ; ?>
<!DOCTYPE html>
	<html>
		<head>
			<title>Berklee Gala</title>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
			<link rel="stylesheet" href="css/themes/BerkleeGala.css" />
			<link rel="stylesheet" href="berklee-mobile.css" />
			<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
			<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
		</head>

		<body>
			<div data-role="page" class="landing" data-theme="a">
				<div data-role="content">
					<img src="images/login-header2.jpg" class="logo" />
					<form action="demo_mobile_home.php" method="POST">
							<label for="bidder_id">Bidder number</label>
							<input type="text" name="bidder_id" data-theme="b" value="433371">
							<input type="submit" value="Start bidding!" data-theme="b">
					</form>
				</div><!-- /content -->
			</div><!-- /page -->


<?php				
	echo "</body></html>";
?>
