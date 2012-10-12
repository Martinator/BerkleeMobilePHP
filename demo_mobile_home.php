<?php
	require_once('_lib/_db.php') ;
	require_once('_time.php') ;
	
	$bidder_id = htmlentities($_POST['bidder_id'],ENT_QUOTES) ;
	if (!$bidder_id){
		$bidder_id = htmlentities($_GET['bidder_id'],ENT_QUOTES) ;
	}
	$bidder_user_id = htmlentities($_COOKIE["bidder_user_id"],ENT_QUOTES) ; 
	
	
	if ($bidder_id){
		$people = mysql_query("SELECT * FROM people WHERE bidder_id='$bidder_id'") ;
	} else {
		$people = mysql_query("SELECT * FROM people WHERE id='$bidder_user_id'") ;
	}
	if (mysql_num_rows($people) > 0){
		while ($thiscat = mysql_fetch_array($people)){
			setcookie('bidder_user_id', $thiscat['id'], time()+21600);
			//echo '<!-- COOKIE: '.$_COOKIE['bidder_user_id'].', ID: '.$thiscat['id'].' -->';

			if ($thiscat[nickname]){
				$howdy = " ($thiscat[nickname]) " ;
			} else {
				$howdy = "" ;
			}
			if ($thiscat[mname]){
				$middle = " $thiscat[mname] " ;
			} else {
				$middle = "" ;
			}
			
			$fname = ($thiscat[fname]) ? $thiscat[fname] : $my_salutation ;
			$name = $fname . $howdy . $middle . " " . $thiscat[lname] ;
		}
	} else {
		if ($docent){
			echo "<meta http-equiv=\"refresh\" content=\"0;docent/index.php\">" ;
		} else {
			echo "<meta http-equiv=\"refresh\" content=\"0;demo_mobile_login.php\">" ;
		}
	}


	$docent = htmlentities($_GET['docent'],ENT_QUOTES) ;
	
	$title_text = ($docent) ? "Docent for: " : "Bidder" ;
	$logout_text = ($docent) ? "<br><a href=\"demo_mobile_logout-docent.php\">LOG OUT</a> " : "" ;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Berklee Gala</title>
		<?php
			if (!$bidder_id && !$bidder_user_id){
				if ($docent){
					echo "<meta http-equiv=\"refresh\" content=\"0;docent/index.php\">" ;
				} else {
					echo "<meta http-equiv=\"refresh\" content=\"0;demo_mobile_login.php\">" ;
				}
			}
		?>
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
				<a href="demo_mobile_logout.php" data-icon="back" rel="external">Logout</a>
				<h1>Encore Gala</h1>

			</div><!-- /header -->
			<div data-role="content">
				
				<?php
					echo "$auction_warning" ;
					if ($auction_closed){
						echo "<meta http-equiv=\"Refresh\" content=\"5;url=demo_mobile-display_auction_winners.php\">" ;
					}
				?>

				<ul data-role="listview"  data-theme="a" class="categories">
					<li  data-role="list-divider">
						<?php echo $name ; ?>
					</li>
					<?php
						if ($auction_closed != 1){
					?>

					<li>
						<a href="demo_mobile_category.php?category=1&docent=<?php echo $docent ; ?>"> <img src="images/button-instruments.jpg"/> <h3>Instruments &amp; Gear</h3> </a>
					</li>
					<li>
						<a href="demo_mobile_category.php?category=6&docent=<?php echo $docent ; ?>"> <img src="images/button-artwork.jpg"/> <h3>Artwork</h3> </a>
					</li>
					<li>
						<a href="demo_mobile_category.php?category=2&docent=<?php echo $docent ; ?>"> <img src="images/button-sports.jpg"/> <h3>Sports</h3> </a>
					</li>
					<li>
						<a href="demo_mobile_category.php?category=7&docent=<?php echo $docent ; ?>"> <img src="images/button-restaurants_shows.jpg"/> <h3>Restaurants/Shows</h3> </a>
					</li>
					<li>
						<a href="demo_mobile_category.php?category=4&docent=<?php echo $docent ; ?>"> <img src="images/button-vacations_hotels.jpg"/> <h3>Vacations/Hotels</h3> </a>
					</li>
					<li>
						<a href="demo_mobile_category.php?category=8&docent=<?php echo $docent ; ?>"> <img src="images/button-gifts_services.jpg"/> <h3>Gifts &amp; Services</h3> </a>
					</li>
					<li>
						<a href="demo_mobile_category.php?category=5&docent=<?php echo $docent ; ?>"> <img src="images/button-events.jpg"/> <h3>Events</h3> </a>
					</li>
					<?php
						}
					?>
					<li data-theme="c" >
						<a href="demo_mobile_donate.php?docent=<?php echo $docent ; ?>" rel="external"> <img src="images/donate.jpg"/> <h3>Fund-A-Future</h3> <!--<p>
						I have not bid on any items.
						</p>--> </a>
					</li>
				</ul>

	
	
	<?php
	
	
	$allmyitems = mysql_query("SELECT DISTINCT item_id FROM bids WHERE bidder_user_id='$bidder_user_id' ORDER BY item_id, bid_amount DESC") ;
	if (mysql_num_rows($allmyitems) > 0){
		while ($thisallmyitem = mysql_fetch_array($allmyitems)){
			$my_items[] = $thisallmyitem[item_id] ;
		}

		foreach($my_items AS $this_item){
			// echo "<b>$this_item</b> " ;
			$topbids = mysql_query("SELECT * FROM bids WHERE item_id=$this_item ORDER BY bid_amount DESC LIMIT 1") ;
			if (mysql_num_rows($topbids) > 0){
				while ($this_topbid = mysql_fetch_array($topbids)){
					// echo "bidder $this_topbid[bidder_user_id] / item $this_topbid[item_id] / \$$this_topbid[bid_amount]<br>" ;
					$key_number = $this_topbid[item_id] ;
					if ($this_topbid[bidder_user_id] == $bidder_user_id){
						$winning_items[$key_number][item_id] = $this_topbid[item_id] ;
						$winning_items[$key_number][bid_amount] = $this_topbid[bid_amount] ;
					} else {
						$losing_items[$key_number][item_id] = $this_topbid[item_id] ;
						$losing_items[$key_number][bid_amount] = $this_topbid[bid_amount] ;
					}
				}
			}
			
		}

	

		echo "<ul data-role=\"listview\"  data-theme=\"c\"  data-divider-theme=\"a\">" ;
		echo "<li data-role=\"list-divider\">Somebody else is winning these items:</li>" ;
		foreach($losing_items AS $item){
			echo "<li><a href=\"demo_mobile_bid.php?id=$item_id&docent=$docent\" rel=\"external\">" ;
			$item_id = $item[item_id] ;
			$getitems = mysql_query("SELECT * FROM items WHERE id='$item_id'") ;
			if (mysql_num_rows($getitems) > 0){
				while ($current_item = mysql_fetch_array($getitems)){
					$item_title = $current_item[title] ;
				}
			}
			echo "<h3>$item_title</h3><p>\$". number_format($item[bid_amount]) ."</p>" ;
			if (!$auction_closed){
				echo "<h3>Get it back!</h3>" ;
			}
			echo "</a></li>" ;
		}
		
		
		

		echo "<li data-role=\"list-divider\">I'm winning these items!</li>" ;
		foreach($winning_items AS $item){
			echo "<li><a href=\"demo_mobile_bid.php?id=$item_id&docent=$docent\" rel=\"external\">" ;
			$item_id = $item[item_id] ;
			$getitems = mysql_query("SELECT * FROM items WHERE id='$item_id'") ;
			if (mysql_num_rows($getitems) > 0){
				while ($current_item = mysql_fetch_array($getitems)){
					$item_title = $current_item[title] ;
				}
			}
			echo "<h3>$item_title</h3><p>\$". number_format($item[bid_amount]) ."</p>" ;
			echo "</a></li>" ;
		}
		
		
		echo "</ul>" ;
		
		

	} else {
		echo "<h3>I have not bid on any items.</h3>" ;
	}
	
	$donations = mysql_query("SELECT * FROM donations WHERE bidder_user_id='$bidder_user_id'") ;
	if (mysql_num_rows($donations) > 0){
		while ($thisdonation = mysql_fetch_array($donations)){
			$half_scholarships += $thisdonation[half_scholarship] ;
			$full_scholarships += $thisdonation[full_scholarship] ;
			$other_amount += $thisdonation[other_amount] ;
		}
	}
	echo "<ul data-role=\"listview\"  data-theme=\"c\"   data-divider-theme=\"a\">" ;
	
	if (($half_scholarships > 0) || ($full_scholarships > 0) || ($other_amount > 0)) {
		echo "<li data-role=\"list-divider\">Fund-A-Future</li>" ;
	}
	if ($half_scholarships > 0){
		echo "<li><p>Tuition &#43; Room &#38; Board:  $half_scholarships &times; \$8,000 = \$" . number_format($half_scholarships * 8000) . "</p></li>" ;
	}
	if ($full_scholarships > 0){
		echo "<li><p>Tuition-only: $full_scholarships &times; \$5,000 = \$" . number_format($full_scholarships * 5000) . "</p></li>" ;
	}
	if ($other_amount > 0){
		echo "<li><p>Other donation: \$". number_format($other_amount) . "</p></li>" ;
	}
	//echo "<tr><td colspan=\"2\"><font face=\"sans-serif\" color=\"#666666\">Total: </font></td><td align=\"right\"><font face=\"sans-serif\" color=\"#666666\">\$".number_format(($half_scholarships * 2500) + ($full_scholarships * 5000) + ($other_amount))."</font></td></tr>" ;
	echo "</ul>" ;

	
	
	//echo "<meta http-equiv=\"Refresh\" content=\"15;url=demo_mobile_home.php?docent=$docent\">" ;
	?>



<?php		
	echo "</div>" ; //close "content"
	echo "</div>" ; //close "page"		
	echo "</body></html>";
?>
