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


	<?php
		$thecat = htmlentities($_GET['category'],ENT_QUOTES) ;


		$docent = htmlentities($_GET['docent'],ENT_QUOTES) ;
		
		$title_text = ($docent) ? "Docent for: " : "Bidder" ;
		$logout_text = ($docent) ? "<br><a href=\"demo_mobile_logout-docent.php\">LOG OUT</a> " : "" ;
	?>


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
			echo "<h3>Live Auction Items!</h3>" ;
			$category_image_url = "button-live_auction.jpg" ;
		} else {
			$categories = mysql_query("SELECT category FROM item_categories WHERE id=$thecat") ;
			if ($thecat == 1){
				$category_name = "Instruments &amp; Gear" ;
			} elseif ($thecat == 2) {
				$category_name = "Sports" ;
			} elseif ($thecat == 4) {
				$category_name = "Vacations &amp; Hotels" ;
			} elseif ($thecat == 5) {
				$category_name = "button-events.jpg" ;
			} elseif ($thecat == 6) {
				$category_name = "Artwork" ;
			} elseif ($thecat == 7) {
				$category_name = "Restaurants &amp; Shows" ;
			} elseif ($thecat == 8) {
				$category_name = "Gifts &amp; Services" ;
			}

			while ($thiscategory = mysql_fetch_array($categories)){
				$category_title = $thiscategory[category] ;
				//echo $category_title ;
			}
		}

		//echo "<hr>". mysql_num_rows($result) ."<hr>" ;

	
		echo "<div data-role=\"page\" data-theme=\"b\">"
		echo "	<div data-role=\"header\" data-position=\"fixed\">"
		echo "		<h1>$category_name</h1>"
		echo "	</div>"
		echo "<div data-role=\"content\">"
	
		echo "<ul data-role=\"listview\"  data-theme=\"c\">" ;
		//echo "<tr><td><font face=\"sans-serif\" color=\"#666666\" size=\"4\"><b>$category_title</b></font></td></tr>" ;
	


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
					echo "<li><a href=\"demo_mobile_bid.php?id=$thisitem[id]&docent=$docent\" rel=\"external\">" ;
					if ($thisitem[image]){
						$picture = "<img src=\"item_images/$thisitem[image]\">" ;
					} else {
						$picture = "" ;
					}
					$thedescription = nl2br($thisitem[description]) ;
					echo "$picture <h3>$thisitem[title]</h3><p>Current bid: \$ ".number_format($current_bid)."</p><p>Bid now!</p>" ;
					if ($thisitem[retail_price] > 0){
						$price = "$ $thisitem[retail_price]" ;
					} else {
						$price = "<i>priceless</i>" ;
					}
					echo "</a></li>" ;
				} else {
					$moveon = 1 ;
				}
			}
			if ($moveon){
				//echo "<tr><td align=\"center\" class=\"bodytext\" colspan=\"8\"><i>There are currently no items in that category.  Please check back later.</i></td></tr>" ;
			}
		} else { 
			echo "<li><p>There are currently no items in that category.  Please check back later.</p</li>" ;
		}
		

	} else {
		echo "<li>";
		echo "<h2 class=\"bodyheader\">Auction Items</h2>" ;
		echo "<p>Please select a category from the navigation at left</p>" ;
		echo "</li>";
	}
		echo "</ul>" ;
		echo "</div>" ;
		echo "</div>" ;
	
	// this comment is superfluous - not just regular fluous.
	?>

<?php				
	echo "</body></html>";
?>
