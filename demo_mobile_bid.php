<?php require_once('_lib/_db.php') ; ?>

<?php require_once('_bidder_info.php') ; ?>

<?php require_once('_time.php') ; ?>


	<html>
	<head>

	
		<title>Berklee Mobile Bidder</title>

		<link rel="stylesheet" type="text/css" href="_mobile.css">	
	
	
	</head>
	<body background="mobile_files/bg-tile-beige.jpg">
	<center>

	<?php
		$id = $_GET['id'];


		$docent = htmlentities($_GET['docent'],ENT_QUOTES) ;
		
		$title_text = ($docent) ? "Docent for: " : "Bidder" ;
		$logout_text = ($docent) ? "<br><a href=\"demo_mobile_logout-docent.php\">LOG OUT</a> " : "" ;
	?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="left"><a href="demo_mobile_home.php?docent=<?php echo $docent ; ?>">Home</a></td>
	<td align="right"><a href="demo_mobile_bid.php?id=<?php echo "$id" ; ?>&docent=<?php echo $docent ; ?>">Refresh</a></td>
	</tr>
	</table>

	<table border="0" width="480" cellspacing="0" cellpadding="0">
	<tr>
	<td><a href="demo_mobile_home.php?docent=<?php echo $docent ; ?>"><img src="mobile_files/gala-logo.jpg"></a></td>
	<td><font face="sans-serif" color="#666666" size="4"><b><?php echo $title_text ; echo $logout_text ; ?><br><br><a href="demo_mobile_home.php?docent=<?php echo $docent ; ?>"><font face="sans-serif" color="#666666"><?php echo $name ; ?></font></a></b></font></td>
	</tr>
	</table>


	<?php

		$submission = htmlentities($_POST['submission'],ENT_QUOTES) ;
		
		if ($submission){
			//--------- SAVE BID ---------
			$bidder_user_id = htmlentities($_POST['bidder_user_id'],ENT_QUOTES) ;
			$item_id = htmlentities($_POST['item_id'],ENT_QUOTES) ;
			$bid_amount = htmlentities($_POST['bid_amount'],ENT_QUOTES) ;
			$bid_amount = str_replace(",","",$bid_amount) ;
			if ($bid_amount > 99999){
				$bid_amount = $opening_bid ;
				$too_high = "<br><font size=\"5\" color=\"#ff0000\">I don't think you meant to bid over \$100,000.  Let's try that again.<br>" ;
			} else {
				$too_high = "" ;
			}
			$opening_bid = htmlentities($_POST['opening_bid'],ENT_QUOTES) ;
			$increase = htmlentities($_POST['increase'],ENT_QUOTES) ;
			$last_bid_amount = htmlentities($_POST['last_bid_amount'],ENT_QUOTES) ;
			//----- check highest bid amount ------
				$bidamounts = mysql_query("SELECT * FROM bids WHERE item_id='$item_id' ORDER BY bid_amount DESC LIMIT 1") ;
				if (mysql_num_rows($bidaoumnts) > 0){
					while ($gettabid = mysql_fetch_array($bidamounts)){
						$last_bid_amount = $gettabid[bid_amount] ;
					}
				}
			//----- /end check highest bid amount ------
			
			$bid_date = date("Y-m-j") ;
			$bid_time = date("H:i:s") ;

			if (($bid_amount >= $opening_bid) && ($bid_amount >= ($last_bid_amount + $increase))){
				$writebid = mysql_query("INSERT bids (bidder_user_id,item_id,bid_amount,bid_date,bid_time) VALUES ('$bidder_user_id','$item_id','$bid_amount','$bid_date','$bid_time')") ;
			} else {
				if (!($bid_amount >= $opening_bid)){
					if ($needed_amount > $opening_bid){
						echo "<center><font color=\"#990000\">The minimum bid for this item is \$".number_format($opening_bid).".<br><br></center>" ;
					} else {
						$needed_amount = $last_bid_amount + $increase ;
						echo "<center><font color=\"#990000\">The minimum bid to take the lead is \$".number_format($needed_amount).".<br><br></center>" ;
					}
				} else {
					$needed_amount = $last_bid_amount + $increase ;
					echo "<center><font color=\"#990000\">The minimum bid to take the lead is \$".number_format($needed_amount).".<br><br></center>" ;
				}
				$showbidform = 1 ;
			}
			//----------------------------
		}

	
		$id = $_GET['id'];
	
		if(is_numeric($id)) {
			$result = mysql_query("SELECT * FROM items WHERE id='$id'") ;
		}
		else {
			$id = null; // toss corrupt value so we don't use it
		}



		if ($submission && !$showbidform){
			echo "<center><font size=\"5\"><font color=\"#CC0000\" face=\"sans-serif\">You are the highest bidder at \$<u>".number_format($bid_amount)."</u>!</font> - <a href=\"demo_mobile_home.php?docent=$docent\"><font face=\"sans-serif\">See more items</font></a></font></center><br><br>" ;
		}
	
		echo "<table border=\"0\" width=\"480\" align=\"center\"><tr><td>" ;
		if (isset($result) && mysql_num_rows($result) > 0){
			while ($thisitem = mysql_fetch_array($result)){
				$item_id = $thisitem[id] ;
				//echo "<b>item_id is $item_id</b><br>" ;
				$retail_price = $thisitem[retail_price] ;
				$opening_bid = $thisitem[opening_bid] ;
				$increase = $thisitem[increase] ;
				//----- GET CURRENT HIGH BID ------
					$current_bid = 0 ;
					$bids = mysql_query("SELECT * FROM bids WHERE item_id='$item_id' ORDER BY bid_amount DESC LIMIT 1") ;
					if (mysql_num_rows($bids) > 0){
						while ($thisbid = mysql_fetch_array($bids)){
							$current_bid = $thisbid[bid_amount] ;
						}
					}
				//--------------------------------
			
	
				$thedescription = nl2br($thisitem[description]) ;
	
				echo "<font size=\"4\" color=\"#666666\" face=\"sans-serif\"><b>$thisitem[title]</b></font><br><br>" ;
				echo "<p>" ;
				if ($thisitem[image]){
					echo "<img src=\"item_images/$thisitem[image]\" width=\"200\" align=\"left\" hspace=\"5\">";
				}
				echo "<font color=\"#666666\" face=\"sans-serif\">$thedescription</font></p>" ;
				echo "<font color=\"#666666\" face=\"sans-serif\">Retail value: \$".number_format($retail_price)."<br>Opening bid: \$ ".number_format($thisitem[opening_bid])."<br><b>Current bid: \$ ".number_format($current_bid)."</b></font><br>" ;
	
				if ($thisitem[donor1_id]){
					$donors = mysql_query("SELECT id,fname,lname,prog_name,website,company FROM people WHERE id = $thisitem[donor1_id]") ;
					while ($thisdonor = mysql_fetch_array($donors)){
						if ($thisdonor[company]){
							$company1 = "$thisdonor[company]" ;
							$name1 = "" ;
						} else {
							$company1 = "" ;
							$name1 = ($thisdonor[prog_name]) ? "$thisdonor[prog_name]" : "$thisdonor[fname] $thisdonor[lname]" ;
						}
						if ($thisdonor[website]){
							$link1 = "<a href=\"http://$thisdonor[website]\" target=\"outside\">" ;
							$endlink1 = "</a>" ;
						} else {
							$link1 = "" ;
							$endlink1 = "" ;
						}
						if ($thisdonor[fname] && $thisdonor[lname]){
							echo "<font color=\"#666666\" face=\"sans-serif\">Donor 1: $link1$company1$name1$endlink1</font><br>" ;
						} else {
							echo "<font color=\"#666666\" face=\"sans-serif\">Donor 1: $link1$company1$name1$endlink1</font><br>" ;
						}
					}
				}
			}
		}
		echo "</td></tr></table>" ;
					
		if ($submission && !$showbidform){
			echo "<center><font size=\"5\"><font color=\"#CC0000\" face=\"sans-serif\">You are the highest bidder at \$<u>".number_format($bid_amount)."</u>!</font> - <a href=\"demo_mobile_home.php?docent=$docent\"><font face=\"sans-serif\">See more items</font></a></font></center><br><br>" ;
			echo "<meta http-equiv=\"Refresh\" content=\"5;url=demo_mobile_home.php?docent=$docent\">" ;
		}

		if ((!$submission || $showbidform) && !$auction_closed){
			//----- NO BID SUBMITTED, SHOW BID FORM ------

			// high bidder text
			$tousbids = mysql_query("SELECT * FROM bids WHERE item_id=$id ORDER BY bid_amount DESC LIMIT 1") ;
			if (mysql_num_rows($tousbids) > 0){
				while ($cebid = mysql_fetch_array($tousbids)){
					$current_topper = $cebid[bidder_user_id] ;
					
					$topper_text = ($cebid[bidder_user_id] == $bidder_user_id) ? "<br><b>You are the current high bidder!</b>" : "" ;
				}
			}
			

			?>
			<center>
			<form action="demo_mobile_bid.php?id=<?php echo $item_id ; ?>&docent=<?php echo $docent ; ?>" method="POST">
			<input type="hidden" name="submission" value="1">
			<?php
				echo $too_high ;
				echo $topper_text ;
			?>
			
			<p><i><font color="#666666" face="sans-serif">The minimum bid to take the lead is $
				<?php 
					if ($current_bid > 0){
						echo number_format($current_bid + $increase) ; 
					} else {
						echo number_format($opening_bid) ;
					}
				?>
			</font></i></p>
			
			<h2><font face="sans-serif">Your bid: $<input type="number" name="bid_amount" size="7"></font></h2>	
			<input type="hidden" name="bidder_user_id" value="<?php echo $bidder_user_id ; ?>">
			<input type="hidden" name="item_id" value="<?php echo $item_id ; ?>">
			<input type="hidden" name="opening_bid" value="<?php echo $opening_bid ; ?>">
			<input type="hidden" name="increase" value="<?php echo $increase ; ?>">
			<input type="hidden" name="last_bid_amount" value="<?php echo $current_bid ; ?>">
			<input type="submit" value="Bid!">
			</form>
			</center>
			<?php
		}

		echo "<br>$auction_warning<br>" ;
	?>




<?php				
	echo "</body></html>";
?>
