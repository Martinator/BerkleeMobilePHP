<?php
	require_once('_lib/_db.php') ;
	
?>

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
		
		<div data-role="page" data-theme="a">
			<div data-role="header" data-position="fixed">
				<h1>Record Auction Winners</h1>

			</div><!-- /header -->
			<div data-role="content">

	<?php
	
		$allitems = mysql_query("SELECT * FROM items WHERE (is_current=1 AND display_on_web=1)") ;
		if (mysql_num_rows($allitems) > 0){
			
			echo "<ul data-role=\"listview\"  data-theme=\"c\"  data-divider-theme=\"a\">" ;
	
			while ($thisitem = mysql_fetch_array($allitems)){
				//echo "<tr>" ;
				//echo "<td>$thisitem[id]</td>" ;
				//echo "</tr>" ;
				$items_array[] = $thisitem[id] ;
			}
			echo "</ul>" ;
		}
		
		
		foreach ($items_array AS $thisone){
			//echo "$thisone<br>" ;
			$allbids = mysql_query("SELECT * FROM bids WHERE item_id=$thisone ORDER BY bid_amount DESC LIMIT 1") ;
			if (mysql_num_rows($allbids) > 0){
				while ($thisbid = mysql_fetch_array($allbids)){
					echo "<li>$thisone - \$$thisbid[bid_amount]</li>" ;
					$item_id = $thisone ;
					$winner_id = $thisbid[bidder_user_id] ;
					$winning_bid = $thisbid[bid_amount] ;
					
					$record_winning_bid = mysql_query("UPDATE items SET winning_bid=$winning_bid, winner_id=$winner_id WHERE id=$item_id") ;
				}
			}
		}
	?>



<?php			
	echo "</div>" ; //close "content"
	echo "</div>" ; //close "page"	
	echo "<meta http-equiv=\"refresh\" content=\"60;demo_mobile_auto-auction-end.php\">" ;

	echo "</body></html>";
?>
