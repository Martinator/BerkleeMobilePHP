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



	<html>
	<head>

		<?php
			if (!$bidder_id && !$bidder_user_id){
				if ($docent){
					echo "<meta http-equiv=\"refresh\" content=\"0;docent/index.php\">" ;
				} else {
					echo "<meta http-equiv=\"refresh\" content=\"0;demo_mobile_login.php\">" ;
				}
			}
		?>
	
		<title>Berklee Mobile Bidder</title>

		<link rel="stylesheet" type="text/css" href="_mobile.css">	
	
	
	</head>
	<body background="mobile_files/bg-tile-beige.jpg">


	<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="left"><a href="demo_mobile_home.php?docent=<?php echo $docent ; ?>">Home</a></td>
	<td align="right"><a href="demo_mobile_home.php?docent=<?php echo $docent ; ?>">Refresh</a></td>
	</tr>
	</table>


	<table border="0" width="480" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td colspan="2">
		<table border="0" width="480" cellspacing="0" cellpadding="0">
		<tr>
		<td><img src="mobile_files/gala-logo.jpg"></td>
		<td><font face="sans-serif" color="#666666" size="6"><b><?php echo $title_text ; echo $logout_text ; ?><br><br><a href="demo_mobile_home.php"><font face="sans-serif" color="#666666" size="6"><?php echo $name ; ?></font></a></b></font></td>
		</tr>
		</table>
	</td>
	</tr>
	<tr>
	<td colspan="2" align="center">
		<?php
			echo "<h2><font color=\"#ff0000\">$auction_warning</font></h2>" ;
			if ($auction_closed){
				echo "<meta http-equiv=\"Refresh\" content=\"5;url=demo_mobile-display_auction_winners.php\">" ;
			}
		?>
	</td>
	</tr>
	<?php
		if ($auction_closed != 1){
	?>
	<tr>
	<td class="bodytext" bgcolor="#9D2063"><a href="demo_mobile_category.php?category=1&docent=<?php echo $docent ; ?>"><img src="mobile_files/button-instruments.jpg" alt="Instruments/Gear"></a></td>
	<!-- <td class="bodytext"><a href="demo_mobile_category.php?category=1">Instruments/Gear</a></td> -->
	<td class="bodytext" bgcolor="#9D2063"><a href="demo_mobile_category.php?category=6&docent=<?php echo $docent ; ?>"><img src="mobile_files/button-artwork.jpg" alt="Artwork"></a></td>
	<!-- <td class="bodytext"><a href="demo_mobile_category.php?category=6">Artwork</a></td> -->
	</tr>
	<tr>
	<td class="bodytext" bgcolor="#9D2063"><a href="demo_mobile_category.php?category=2&docent=<?php echo $docent ; ?>"><img src="mobile_files/button-sports.jpg" alt="Sports"></a></td>
	<!-- <td class="bodytext"><a href="demo_mobile_category.php?category=2">Sports</a></td> -->
	<td class="bodytext" bgcolor="#9D2063"><a href="demo_mobile_category.php?category=7&docent=<?php echo $docent ; ?>"><img src="mobile_files/button-restaurants_shows.jpg" alt="Restaurants/Shows"></a></td>
	<!-- <td class="bodytext"><a href="demo_mobile_category.php?category=7">Restaurants/Shows</a></td> -->
	</tr>
	<tr>
	<td class="bodytext" bgcolor="#9D2063"><a href="demo_mobile_category.php?category=4&docent=<?php echo $docent ; ?>"><img src="mobile_files/button-vacations_hotels.jpg" alt="Vacations/Hotels"></a></td>
	<!-- <td class="bodytext"><a href="demo_mobile_category.php?category=4">Vacations/Hotels</a></td> -->
	<td class="bodytext" bgcolor="#9D2063"><a href="demo_mobile_category.php?category=8&docent=<?php echo $docent ; ?>"><img src="mobile_files/button-gifts_services.jpg" alt="Gifts &amp; Services"></a></td>
	<!-- <td class="bodytext"><a href="demo_mobile_category.php?category=8">Gifts &amp; Services</a></td> -->
	</tr>
	<tr>
	<td class="bodytext" bgcolor="#9D2063"><a href="demo_mobile_category.php?category=5&docent=<?php echo $docent ; ?>"><img src="mobile_files/button-events.jpg" alt="Events"></a></td>
	<!-- <td class="bodytext"><a href="demo_mobile_category.php?category=5">Events</a></td> -->
	<td class="bodytext" bgcolor="#9D2063"><a href="demo_mobile_category.php?category=live&docent=<?php echo $docent ; ?>"><img src="mobile_files/button-live_auction.jpg" alt="Live Auction"></a></td>
	<!-- <td class="bodytext"><a href="demo_mobile_category.php?category=1">Live Auction</a></td> -->
	</tr>
	<tr>
	<td colspan="4">&nbsp;</td>
	</tr>
	<?php
		}
	?>
	<tr>
	<td class="bodytext" colspan="2" valign="top" align="center"><a href="demo_mobile_donate.php?docent=<?php echo $docent ; ?>"><img src="images/mobile_demo/donate.jpg" alt="Donate"><br>Fund-A-Future</a></td>
	</tr>
	</table>

	
	
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

		echo "<br><br>" ;

		echo "<table border=\"1\" align=\"center\" width=\"800\">" ;
		echo "<tr><td valign=\"top\">" ;
	
		echo "<table border=\"0\" align=\"center\" width=\"400\">" ;
		echo "<tr><td colspan=\"2\"><font face=\"sans-serif\" color=\"#666666\"><b><u>Somebody else is winning these items:</u></b></font></td></tr>" ;
		foreach($losing_items AS $item){
			echo "<tr>" ;
			$item_id = $item[item_id] ;
			$getitems = mysql_query("SELECT * FROM items WHERE id='$item_id'") ;
			if (mysql_num_rows($getitems) > 0){
				while ($current_item = mysql_fetch_array($getitems)){
					$item_title = $current_item[title] ;
				}
			}
			echo "<td><font face=\"sans-serif\" color=\"#666666\">&#8212;<a href=\"demo_mobile_bid.php?id=$item_id&docent=$docent\">$item_title</a>&nbsp;&nbsp;&nbsp; </font></td><td align=\"right\"><font face=\"sans-serif\" color=\"#666666\"><b>\$". number_format($item[bid_amount]) ."</b></font></td>" ;
			if (!$auction_closed){
				echo "<td>&nbsp;&nbsp;&nbsp;<a href=\"demo_mobile_bid.php?id=$item_id&docent=$docent\"><img src=\"mobile_files/button-get_it_back.jpg\" alt=\"Get it back!\" width=\"80\"></a></td>" ;
			}
			echo "<tr>" ;
		}
		echo "</table>" ;
		
		echo "</td><td valign=\"top\">" ;

		echo "<table border=\"0\" align=\"center\" width=\"400\">" ;
		echo "<tr><td colspan=\"2\"><font face=\"sans-serif\" color=\"#666666\"><b><u>I'm winning these items!</u></b></font></td></tr>" ;
		foreach($winning_items AS $item){
			echo "<tr>" ;
			$item_id = $item[item_id] ;
			$getitems = mysql_query("SELECT * FROM items WHERE id='$item_id'") ;
			if (mysql_num_rows($getitems) > 0){
				while ($current_item = mysql_fetch_array($getitems)){
					$item_title = $current_item[title] ;
				}
			}
			echo "<td><a href=\"demo_mobile_bid.php?id=$item_id&docent=$docent\"><font face=\"sans-serif\" color=\"#666666\">&#8212;$item_title&nbsp;&nbsp;&nbsp; </font></td><td align=\"right\"><font face=\"sans-serif\" color=\"#666666\">\$". number_format($item[bid_amount]) ."</font></a></td>" ;
			echo "<tr>" ;
		}
		echo "</table>" ;
		
		echo "</td></tr></table>" ;
		
		echo "<br><hr width=\"400\" align=\"center\"><br>" ;

	} else {
		echo "<center>I have not bid on any items.</center>" ;
	}
	
	$donations = mysql_query("SELECT * FROM donations WHERE bidder_user_id='$bidder_user_id'") ;
	if (mysql_num_rows($donations) > 0){
		while ($thisdonation = mysql_fetch_array($donations)){
			$half_scholarships += $thisdonation[half_scholarship] ;
			$full_scholarships += $thisdonation[full_scholarship] ;
			$other_amount += $thisdonation[other_amount] ;
		}
	}
	echo "<table border=\"0\" align=\"center\" width=\"480\">" ;
	if (($half_scholarships > 0) || ($full_scholarships > 0) || ($other_amount > 0)) {
		echo "<tr><td colspan=\"3\"><font face=\"sans-serif\" color=\"#666666\"><b><u>Fund-A-Future</u></b></font></td></tr>" ;
	}
	if ($half_scholarships > 0){
		echo "<tr><td><font face=\"sans-serif\" color=\"#666666\">Tuition &#43; Room &#38; Board: </font></td><td><font face=\"sans-serif\" color=\"#666666\">$half_scholarships &nbsp;&nbsp; x &nbsp;&nbsp; \$8,000 = </font></td><td align=\"right\"><font face=\"sans-serif\" color=\"#666666\">\$" . number_format($half_scholarships * 8000) . "</font></td></tr>" ;
	}
	if ($full_scholarships > 0){
		echo "<tr><td><font face=\"sans-serif\" color=\"#666666\">Tuition-only: </font></td><td><font face=\"sans-serif\" color=\"#666666\">$full_scholarships &nbsp;&nbsp; x &nbsp;&nbsp; \$5,000 = </font></td><td align=\"right\"><font face=\"sans-serif\" color=\"#666666\">\$" . number_format($full_scholarships * 5000) . "</font></td></tr>" ;
	}
	if ($other_amount > 0){
		echo "<tr><td colspan=\"2\"><font face=\"sans-serif\" color=\"#666666\">Other donation: </font></td><td align=\"right\"><font face=\"sans-serif\" color=\"#666666\">\$". number_format($other_amount) . "</font></td></tr>" ;
	}
	echo "<tr><td colspan=\"3\">&nbsp;</td></tr>" ;
	//echo "<tr><td colspan=\"2\"><font face=\"sans-serif\" color=\"#666666\">Total: </font></td><td align=\"right\"><font face=\"sans-serif\" color=\"#666666\">\$".number_format(($half_scholarships * 2500) + ($full_scholarships * 5000) + ($other_amount))."</font></td></tr>" ;
	echo "</table>" ;


	
	echo $logout = "<br><center><a href=\"demo_mobile_logout.php\">LOG OUT</a></center> " ;

	echo "<meta http-equiv=\"Refresh\" content=\"15;url=demo_mobile_home.php?docent=$docent\">" ;
	?>



<?php				
	echo "</body></html>";
?>
