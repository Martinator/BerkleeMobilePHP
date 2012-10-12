<?php require_once('_lib/_db.php') ; ?>
<?php require_once('_bidder_info.php') ; ?>
<?php require_once('_time.php') ; ?>
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
				<h1>Silent Auction Winners</h1>

			</div><!-- /header -->

			<div data-role="content">

	<?php
		$id = $_GET['id'];


		$docent = htmlentities($_GET['docent'],ENT_QUOTES) ;
		
		$title_text = ($docent) ? "Docent for: " : "Bidder" ;
		$logout_text = ($docent) ? "<br><a href=\"demo_mobile_logout-docent.php\">LOG OUT</a> " : "" ;
	?>


<!-- --------------------------------------------------------------------------------------------------------- -->


	<?php
	
	//	today's date
		$todaynum = date("Y-m-d") ;
	//	echo "Today is $todaynum <p>" ;
		
		
		// connect to DB
		require_once('_lib/_db.php') ;
	//	ORDER results by chosen column
		
		$startfrom = htmlentities($_GET['startfrom'],ENT_QUOTES) ;
		if ($startfrom<1){
			$startfrom = 0 ;
		} else {
			$donothing  = 1 ;
		}
	
		// echo "start from: $startfrom<p>" ;
		
	//	$startfrom = 1 ;
		$themax = 9 ;
		$result = mysql_query("SELECT items.id,items.title,items.winner_id,items.catalogue_number,people.id,people.fname,people.lname,salutations.salutation AS salutation_text FROM items LEFT JOIN people ON items.winner_id=people.id LEFT JOIN salutations ON people.salutation=salutations.id WHERE items.winner_id>0 ORDER BY people.lname,people.fname LIMIT $startfrom,$themax", $db) ;
	
		if (mysql_num_rows($result) > 0){
			echo "<ul data-role=\"listview\"  data-theme=\"c\" data-inset=\"true\"  data-divider-theme=\"a\">" ;
			echo "<li  data-role=\"list-divider\"><h3>Winner</h3><p>Item(s) Won</p></li>" ;
			$lastperson = "" ;
			while ($thisitem = mysql_fetch_array($result)){
				if ($thisitem[winner_id]){
					echo "<li>";
					if ($thisitem[winner_id] == $lastperson){
						echo "" ;
					} else {
						$fname = ($thisitem[fname]) ? $thisitem[fname] : $thisitem[salutation_text] ;
						echo "<a href=\"check_out_invoice.php?id=$thisitem[winner_id]\"><h3>$fname $thisitem[lname]</h3>" ;
					}

					echo "<p>$thisitem[title]</p>" ;
					if ($thisitem[winner_id] == $lastperson){
						echo "" ;
					} else {
						$fname = ($thisitem[fname]) ? $thisitem[fname] : $thisitem[salutation_text] ;
						echo "</a>" ;
					}
					echo "</li>";

					$lastperson = $thisitem[winner_id] ;
				} else {
					$donothing = "" ;
				}
			}
			echo "</ul>" ;
			$startfrom = $startfrom + 9 ;
			echo "<meta http-equiv=\"Refresh\" content=\"10;url=demo_mobile-display_auction_winners.php?startfrom=$startfrom\">" ;
			$nextlink = "demo_mobile-display_auction_winners.php?startfrom=$startfrom" ;
		} else { 
			echo "<h2>Retrieving winners...</h2>" ;
			$startfrom = 0 ;
			echo "<meta http-equiv=\"Refresh\" content=\"2;url=demo_mobile-display_auction_winners.php?startfrom=$startfrom\">" ;
			$nextlink = "demo_mobile-display_auction_winners.php?startfrom=$startfrom" ;
		}
		
		
		echo "<a href=\"$nextlink\" data-role=\"button\" rel=\"external\">NEXT</a>" ;
	
	
	
	?>

<!-- --------------------------------------------------------------------------------------------------------- -->
 
			</div><!-- /content -->

		</div><!-- /page -->

	</body>
</html>