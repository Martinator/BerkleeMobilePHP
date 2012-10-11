<?php require_once('_lib/_db.php') ; ?>

<?php require_once('_bidder_info.php') ; ?>

	<html>
	<head>
	
	
		<title>Berklee Mobile Bidder</title>

		<link rel="stylesheet" type="text/css" href="_mobile.css">	
	
	
	</head>
	<body background="mobile_files/bg-tile-beige.jpg">
	<center>


	<?php
		$thecat = htmlentities($_GET['category'],ENT_QUOTES) ;


		$docent = htmlentities($_GET['docent'],ENT_QUOTES) ;
		
		$title_text = ($docent) ? "Docent for: " : "Bidder" ;
		$logout_text = ($docent) ? "<br><a href=\"demo_mobile_logout-docent.php\">LOG OUT</a> " : "" ;
	?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="left"><a href="demo_mobile_home.php?docent=<?php echo $docent ; ?>">Home</a></td>
	<td align="right"><a href="demo_mobile_category.php?category=<?php echo "$thecat" ; ?>&docent=<?php echo $docent ; ?>">Refresh</a></td>
	</tr>
	</table>

	<table border="0" width="480" cellspacing="0" cellpadding="0">
	<tr>
	<td><a href="demo_mobile_home.php?docent=<?php echo $docent ; ?>"><img src="mobile_files/gala-logo.jpg"></a></td>
	<td><font face="sans-serif" color="#666666" size="4"><b><?php echo $title_text ; echo $logout_text ; ?><br><br><a href="demo_mobile_home.php?docent=<?php echo $docent ; ?>"><font face="sans-serif" color="#666666"><?php echo $name ; ?></font></a></b></font></td>
	</tr>
	</table>

	<?php





	$thecat = htmlentities($_GET['category'],ENT_QUOTES) ;
	// the cat must either be a number or specifically 'live'
	
	$thecat_is_valid = (is_numeric($thecat) || $thecat == 'live');
	
	if ($thecat && $thecat_is_valid){
		if ($thecat == "live"){ 
			$result = mysql_query("SELECT * FROM items WHERE id='1118' OR id='1119' OR id='1120' ORDER BY id DESC") ;
		} else {
			$query = "SELECT * FROM items WHERE category='$thecat' AND is_current='1' ORDER BY retail_price DESC" ;
			$result = mysql_query("$query") ;
		}
		//echo "<br><br>$query<br><br>" ;
	
		if ($thecat == "live"){
			echo "<font face=\"sans-serif\" color=\"#666666\">Live Auction Items!</font><p>" ;
			$category_image_url = "button-live_auction.jpg" ;
		} else {
			$categories = mysql_query("SELECT category FROM item_categories WHERE id=$thecat") ;
			if ($thecat == 1){
				$category_image_url = "button-instruments.jpg" ;
			} elseif ($thecat == 2) {
				$category_image_url = "button-sports.jpg" ;
			} elseif ($thecat == 4) {
				$category_image_url = "button-vacations_hotels.jpg" ;
			} elseif ($thecat == 5) {
				$category_image_url = "button-events.jpg" ;
			} elseif ($thecat == 6) {
				$category_image_url = "button-artwork.jpg" ;
			} elseif ($thecat == 7) {
				$category_image_url = "button-restaurants_shows.jpg" ;
			} elseif ($thecat == 8) {
				$category_image_url = "button-gifts_services.jpg" ;
			}

			while ($thiscategory = mysql_fetch_array($categories)){
				$category_title = $thiscategory[category] ;
				//echo $category_title ;
			}
		}

		//echo "<hr>". mysql_num_rows($result) ."<hr>" ;

	
		echo "<table border=\"0\" width=\"480\" align=\"center\">" ;
		//echo "<tr><td><font face=\"sans-serif\" color=\"#666666\" size=\"4\"><b>$category_title</b></font></td></tr>" ;
		echo "<tr><td bgcolor=\"#9D2063\"><img src=\"mobile_files/$category_image_url\"></font></td></tr>" ;
		echo "<tr><td>&nbsp;</td></tr>" ;

		if (mysql_num_rows($result) > 0){
			while ($thisitem = mysql_fetch_array($result)){
				if ($thisitem[display_on_web] && $thisitem[is_current]){
					$current_item_id = $thisitem[id] ;
					//----- GET CURRENT HIGH BID ------
						$current_bid = 0 ;
						$bids = mysql_query("SELECT * FROM bids WHERE item_id='$current_item_id' ORDER BY bid_amount DESC LIMIT 1") ;
						if (mysql_num_rows($bids) > 0){
							while ($thisbid = mysql_fetch_array($bids)){
								$current_bid = $thisbid[bid_amount] ;
							}
						}
					//--------------------------------
					echo "<tr><td align=\"left\" valign=\"top\" class=\"bodytext\" colspan=\"3\">" ;
					if ($thisitem[image]){
						$picture = "<a href=\"demo_mobile_bid.php?id=$thisitem[id]&docent=$docent\"><img src=\"item_images/$thisitem[image]\" width=\"200\" align=\"right\"></a>" ;
					} else {
						$picture = "" ;
					}
					$thedescription = nl2br($thisitem[description]) ;
					echo "$picture<a href=\"demo_mobile_bid.php?id=$thisitem[id]&docent=$docent\"><b><font size=\"+1\" color=\"666666\" face=\"sans-serif\">$thisitem[title]</font></b></a><br><br><font face=\"sans-serif\" color=\"#666666\">Current bid: \$ ".number_format($current_bid)."</font><br><br><br><a href=\"demo_mobile_bid.php?id=$thisitem[id]&docent=$docent\"><font size=\"+1\" color=\"#ff0000\" face=\"sans-serif\">Bid now!</font></a></td></tr>" ;
					if ($thisitem[retail_price] > 0){
						$price = "$ $thisitem[retail_price]" ;
					} else {
						$price = "<i>priceless</i>" ;
					}
					echo "<td class=\"bodytext\" valign=\"top\"><p></td>" ;
					echo "<td align=\"right\" valign=\"top\" class=\"bodytext\">" ;
					echo "</td></tr>" ;
					echo "<tr><td colspan=\"3\"><p><hr><p></td></tr>" ;
				} else {
					$moveon = 1 ;
				}
			}
			if ($moveon){
				//echo "<tr><td align=\"center\" class=\"bodytext\" colspan=\"8\"><i>There are currently no items in that category.  Please check back later.</i></td></tr>" ;
			}
		} else { 
			echo "<tr><td align=\"center\" class=\"bodytext\" colspan=\"8\"><i>There are currently no items in that category.  Please check back later.</i></td></tr>" ;
		}
		echo "</table>" ;
	} else {
		echo "<span class=\"bodyheader\">Auction Items</span><p>" ;
		echo "Please select a category from the navigation at left<p>" ;
	}
	
	// this comment is superfluous - not just regular fluous.
	?>

<?php				
	echo "</center></body></html>";
?>
