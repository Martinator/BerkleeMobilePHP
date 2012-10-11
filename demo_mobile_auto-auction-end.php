<?php
	require_once('_lib/_db.php') ;
	
?>

	<?php 
	echo "<html><head><body bgcolor=\"#FFCC99\">" ;
	?>

	<?php
	
		$allitems = mysql_query("SELECT * FROM items WHERE (is_current=1 AND display_on_web=1)") ;
		if (mysql_num_rows($allitems) > 0){
			echo "<br><h1 align=\"center\">Record Auction Winners</h1>" ;
			echo "<table cellpadding=\"5\" align=\"center\">" ;
			while ($thisitem = mysql_fetch_array($allitems)){
				//echo "<tr>" ;
				//echo "<td>$thisitem[id]</td>" ;
				//echo "</tr>" ;
				$items_array[] = $thisitem[id] ;
			}
			echo "</table>" ;
		}
		
		
		foreach ($items_array AS $thisone){
			//echo "$thisone<br>" ;
			$allbids = mysql_query("SELECT * FROM bids WHERE item_id=$thisone ORDER BY bid_amount DESC LIMIT 1") ;
			if (mysql_num_rows($allbids) > 0){
				while ($thisbid = mysql_fetch_array($allbids)){
					echo "$thisone - \$$thisbid[bid_amount]<br>" ;
					$item_id = $thisone ;
					$winner_id = $thisbid[bidder_user_id] ;
					$winning_bid = $thisbid[bid_amount] ;
					
					$record_winning_bid = mysql_query("UPDATE items SET winning_bid=$winning_bid, winner_id=$winner_id WHERE id=$item_id") ;
				}
			}
		}
	?>



<?php				
	echo "<meta http-equiv=\"refresh\" content=\"60;demo_mobile_auto-auction-end.php\">" ;

	echo "</body></html>";
?>
