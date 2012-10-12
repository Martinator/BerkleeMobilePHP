<?php require_once('_lib/_db.php') ; ?>

<?php require_once('_bidder_info.php') ; ?>

<?php require_once('_time.php') ; ?>
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
				<h1>Leader Board</h1>

			</div><!-- /header -->
			<div data-role="content">


	<?php
		echo "<meta http-equiv=\"Refresh\" content=\"15;url=demo_mobile_leader-board.php\">" ;
	
		echo "<h2>$auction_warning<h2>" ;

		
		echo "<ul data-role=\"listview\"  data-theme=\"c\" data-inset=\"true\"  data-divider-theme=\"a\">" ;
		echo "<li data-role=\"list-divider\">Bidder: $name</li>" ;
		echo "<li>" ;
		$items = mysql_query("SELECT * FROM items WHERE (is_current='1' AND display_on_web='1') ORDER BY RAND() LIMIT 1") ;
		if (mysql_num_rows($items) > 0){
			while ($thisitem = mysql_fetch_array($items)){
				
				if ($thisitem[image]){
					echo "<img src=\"item_images/$thisitem[image]\" width=\"200\" align=\"left\" hspace=\"5\">";
				}
				echo "<h3>$thisitem[title]</h3>" ;
				echo "<p>$thedescription</p>" ;
				//------ get current bid ---------------
					$item_id = $thisitem[id] ;
					$bids = mysql_query("SELECT * FROM bids WHERE item_id=$item_id ORDER BY bid_amount DESC LIMIT 1") ;
					if (mysql_num_rows($bids) > 0){
						while ($thisbid = mysql_fetch_array($bids)){
							$current_bid = $thisbid[bid_amount] ;
						}
					}
				//-------/end get current bid ----------
				echo "<p>Retail value: $price</p><p>Opening bid: $ ".number_format($thisitem[opening_bid])."</p><p><strongb>Current bid: \$ ".number_format($current_bid)."</strong></p>" ;
				echo "</li>" ;
				
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
							$link1 = "<a href=\"http://$thisdonor[website]\" target=\"outside\" rel=\"external\">" ;
							$endlink1 = "</a>" ;
						} else {
							$link1 = "" ;
							$endlink1 = "" ;
						}
						if ($thisdonor[fname] && $thisdonor[lname]){
							echo "<li>$link1 Donor 1: $company1$name1$endlink1</li>" ;
						} else {
							echo "<li>$link1 Donor 1: $company1$name1$endlink1</li>" ;
						}
					}
				}
			}
		}
		echo "</ul>" ;
		
		
	?>




<?php			
	echo "</div>" ; //close "content"
	echo "</div>" ; //close "page"	
	echo "</body></html>";
?>
