<?php
	require_once('_lib/_db.php') ;
	
?>

	<?php 
	echo "<html><head><body bgcolor=\"#FFCC99\">" ;
	?>

	<?php
	
		$allbigs = mysql_query("SELECT bids.*,people.fname,people.lname,items.title FROM bids LEFT JOIN people ON bids.bidder_user_id=people.id LEFT JOIN items ON bids.item_id=items.id WHERE bid_amount>'9999' ORDER BY bids.bidder_user_id,bids.bid_amount DESC") ;
		if (mysql_num_rows($allbigs) > 0){
			echo "<br><h1 align=\"center\">Bids of \$10,000 and up</h1>" ;
			echo "<table cellpadding=\"5\" align=\"center\">" ;
			while ($thisbiggun = mysql_fetch_array($allbigs)){
				echo "<tr>" ;
				echo "<td>$thisbiggun[fname] $thisbiggun[lname]</td><td><i>$thisbiggun[title]</i></td><td>\$".number_format($thisbiggun[bid_amount])."</td>" ;
				echo "</tr>" ;
			}
			echo "</table>" ;
		} else {
			echo "<p><b>No monster bids found.</b></p>" ;
		}
	
	
		echo "<meta http-equiv=\"Refresh\" content=\"15;url=demo_mobile_5-figure-check.php\">" ;
	?>



<?php				
	echo "</body></html>";
?>
