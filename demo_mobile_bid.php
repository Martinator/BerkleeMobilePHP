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
				<h1>Bid</h1>

			</div><!-- /header -->
			<div data-role="content">

	<?php
		$id = $_GET['id'];


		$docent = htmlentities($_GET['docent'],ENT_QUOTES) ;
		
		$title_text = ($docent) ? "Docent for: " : "Bidder" ;
		$logout_text = ($docent) ? "<br><a href=\"demo_mobile_logout-docent.php\">LOG OUT</a> " : "" ;
	?>




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
				$too_high = "<h2>I don't think you meant to bid over \$100,000.  Let's try that again.<h2>" ;
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
						echo "<h2>The minimum bid for this item is \$".number_format($opening_bid).".</h2>" ;
					} else {
						$needed_amount = $last_bid_amount + $increase ;
						echo "<h2>The minimum bid to take the lead is \$".number_format($needed_amount).".</h2>" ;
					}
				} else {
					$needed_amount = $last_bid_amount + $increase ;
					echo "<h2>The minimum bid to take the lead is \$".number_format($needed_amount).".</h2>" ;
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
			echo "<h2>You are the highest bidder at \$".number_format($bid_amount)."!</h2>";
			 echo "<a href=\"demo_mobile_home.php?docent=$docent\" data-role=\"button\" rel=\"external\">See more items</a>" ;
		}
	
		echo "<ul data-role=\"listview\" data-inset=\"true\"  data-theme=\"c\" class=\"item-detail\">" ;
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

				echo "<li>" ;
				if ($thisitem[image]){
					echo "<img src=\"item_images/$thisitem[image]\" >";
				}echo "<h3>$thisitem[title]</h3>" ;
				echo "<p>$thedescription<p>" ;
				echo "</li>";
				echo "<li><p>Retail value: \$".number_format($retail_price)."</p></li>" ;
				echo "<li><p>Opening bid: \$ ".number_format($thisitem[opening_bid])."</p></li>" ;
				echo "<li><h3>Current bid: \$ ".number_format($current_bid)."</h3></li>" ;
	
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
							$link1 = "<li><a href=\"http://$thisdonor[website]\" target=\"outside\" rel=\"external\">" ;
							$endlink1 = "</a></li>" ;
						} else {
							$link1 = "" ;
							$endlink1 = "" ;
						}
						if ($thisdonor[fname] && $thisdonor[lname]){
							echo "$link1 Donor 1: $company1$name1$endlink1" ;
						} else {
							echo "$link1 Donor 1: $company1$name1$endlink1" ;
						}
					}
				}
			}
		}
		echo "</ul>" ;
					
		if ($submission && !$showbidform){
			echo "<h2>You are the highest bidder at \$".number_format($bid_amount)."!</h2>";
			echo "<a href=\"demo_mobile_home.php?docent=$docent\" data-role=\"button\" rel=\"external\">See more items</a>" ;
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
			
			<form action="demo_mobile_bid.php?id=<?php echo $item_id ; ?>&docent=<?php echo $docent ; ?>" method="POST">
			<input type="hidden" name="submission" value="1">
			
			
			
			<ul data-role="listview" data-inset="true"  data-theme="c" class="item-detail">
			<?php
				echo $too_high ;
				echo $topper_text ;
			?>
			
			
			
			
			<li  data-role="list-divider">The minimum bid to take the lead is $
				<?php 
					if ($current_bid > 0){
						echo number_format($current_bid + $increase) ; 
					} else {
						echo number_format($opening_bid) ;
					}
				?>
			</li>
			
		

						<li>
							<label for="bid_amount">Your bid:</label>
							<input type="number" name="bid_amount"  id="bid_amount" />
							<input type="hidden" name="bidder_user_id" value="<?php echo $bidder_user_id ; ?>">
							<input type="hidden" name="item_id" value="<?php echo $item_id ; ?>">
							<input type="hidden" name="opening_bid" value="<?php echo $opening_bid ; ?>">
							<input type="hidden" name="increase" value="<?php echo $increase ; ?>">
							<input type="hidden" name="last_bid_amount" value="<?php echo $current_bid ; ?>">
						</li>
					</ul>
				<input type="submit" data-theme="a" value="Bid!">

				</form>

			<?php
		}

		echo "<h2>$auction_warning<h2>" ;
	?>




<?php		
	echo "</div>" ; //close "content"
	echo "</div>" ; //close "page"	
	echo "</body></html>";
?>
