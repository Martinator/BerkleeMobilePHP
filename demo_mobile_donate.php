<?php require_once('_lib/_db.php') ; ?>
<?php require_once('_bidder_info.php') ; ?>
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

		<div data-role="page" data-theme="b">
			<div data-role="header" data-position="fixed">
				<a hhref="demo_mobile_home.php?docent=<?php echo $docent ; ?>" data-icon="back" rel="external">Home</a>
				<h1>Fund-A-Future</h1>

			</div><!-- /header -->

			<div data-role="content">
				

	<?php
	
		$submission = htmlentities($_POST['submission'],ENT_QUOTES) ;
		$donor_id = htmlentities($_POST['donor_id'],ENT_QUOTES) ;
		$half_scholarship = htmlentities($_POST['half_scholarship'],ENT_QUOTES) ;
		$full_scholarship = htmlentities($_POST['full_scholarship'],ENT_QUOTES) ;
		$other_amount = htmlentities($_POST['other_amount'],ENT_QUOTES) ;
		$other_amount = str_replace(",","",$other_amount) ;
		

		$date = date("Y-m-j") ;
		$time = date("H:i:s") ;
		
		//if ($submission){
		//	echo "<meta http-equiv=\"Refresh\" content=\"5;url=demo_mobile_home.php\">" ;
		//}

		echo "<ul data-role=\"listview\"  data-theme=\"c\" data-inset=\"true\"  data-divider-theme=\"a\">" ;
		if ($half_scholarship > 0){
			$record_donation = mysql_query("INSERT donations (bidder_user_id,half_scholarship,date,time) VALUES ('$bidder_user_id','$half_scholarship','$date','$time')") ;
			echo "<li>Half scholarships (\$2,500) &times; $half_scholarship = \$" . number_format($half_scholarship * 2500) . "</li>" ;
		}
		if ($full_scholarship > 0){
			$record_donation = mysql_query("INSERT donations (bidder_user_id,full_scholarship,date,time) VALUES ('$bidder_user_id','$full_scholarship','$date','$time')") ;
			echo "<li>Tuition scholarships (\$5,000) &times; $full_scholarship =\$" . number_format($full_scholarship * 5000) . "</li>" ;
		}
		if ($other_amount > 0){
			$record_donation = mysql_query("INSERT donations (bidder_user_id,other_amount,date,time) VALUES ('$bidder_user_id','$other_amount','$date','$time')") ;
			echo "<li>Other donation: <\$". number_format($other_amount) . "</li>" ;
		}
		echo "</ul>" ;
	
	?>


	<?php
		if ($submission != 1){
	?>
		<form action="demo_mobile_donate.php" method="POST">
					<h3>Support these worthy initiatives at Berklee!</h3>
					
					<input type="hidden" name="submission" value="1">
					<input type="hidden" name="donor_id" value="<?php echo $bidder_user_id ; ?>">
		
		
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
							<legend>
								Scholarships
							</legend>
							<label for="half_scholarship">Tuition &#43; Room &#38; Board scholarship ($8,000 each)</label>

							<select name="half_scholarship" id="half_scholarship">
								<option value="">Tuition &#43; Room &#38; Board</option>
								<option value="1">1 &times; $8,000</option>
								<option value="2">2 &times; $8,000</option>
								<option value="3">3 &times; $8,000</option>
								<option value="4">4 &times; $8,000</option>
								<option value="5">5 &times; $8,000</option>
								<option value="6">6 &times; $8,000</option>
								<option value="7">7 &times; $8,000</option>
								<option value="8">8 &times; $8,000</option>
								<option value="9">9 &times; $8,000</option>
								<option value="10">10 &times; $8,000</option>
							</select>

							<label for="full_scholarship">Tuition-only scholarship ($5,000 each)</label>

							<select name="full_scholarship" id="full_scholarship">
								<option value="">Tuition-only</option>
								<option value="1">1 &times; $5,000</option>
								<option value="2">2 &times; $5,000</option>
								<option value="3">3 &times; $5,000</option>
								<option value="4">4 &times; $5,000</option>
								<option value="5">5 &times; $5,000</option>
								<option value="6">6 &times; $5,000</option>
								<option value="7">7 &times; $5,000</option>
								<option value="8">8 &times; $5,000</option>
								<option value="9">9 &times; $5,000</option>
								<option value="10">10 &times; $5,000</option>
							</select>
						</fieldset>
					</div>

					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="other_amount">Other amount</label>
						<input type="number" name="other_amount" id="other_amount"  placeholder="Other Amount" value=""  />
					</div>
					<input type="submit" data-theme="a"  value="DONATE!">
				</form>
	
	<?php
		} else {
			echo "<h2>Thank you for your generous donations to Fund the Futures of Boston's inner-city youth!</h2>" ;
			echo "<meta http-equiv=\"Refresh\" content=\"5;url=demo_mobile_home.php\">" ;
		}
	?>


<?php		
	echo "</div>" ; //close "content"
	echo "</div>" ; //close "page"	
	echo "</body></html>";
?>
