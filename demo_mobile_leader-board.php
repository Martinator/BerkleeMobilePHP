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

	<table border="0" width="480" cellspacing="0" cellpadding="0">
	<tr>
	<td><a href="demo_mobile_home.php"><img src="mobile_files/gala-logo.jpg"></a></td>
	<td><font face="sans-serif" color="#666666" size="4"><b>Bidder<br><br><a href="demo_mobile_home.php"><font face="sans-serif" color="#666666"><?php echo $name ; ?></font></a></b></font></td>
	</tr>
	</table>


	<?php
		echo "<meta http-equiv=\"Refresh\" content=\"15;url=demo_mobile_leader-board.php\">" ;
	
		echo "<br>$auction_warning<br>" ;

		
		echo "<table border=\"0\" width=\"480\">" ;
		echo "<tr><td>" ;
		$items = mysql_query("SELECT * FROM items WHERE (is_current='1' AND display_on_web='1') ORDER BY RAND() LIMIT 1") ;
		if (mysql_num_rows($items) > 0){
			while ($thisitem = mysql_fetch_array($items)){
				echo "<font size=\"4\" color=\"#666666\" face=\"sans-serif\"><b>$thisitem[title]</b></font><br><br>" ;
				echo "<p>" ;
				if ($thisitem[image]){
					echo "<img src=\"item_images/$thisitem[image]\" width=\"200\" align=\"left\" hspace=\"5\">";
				}
				echo "<font color=\"#666666\" face=\"sans-serif\">$thedescription</font></p>" ;
				//------ get current bid ---------------
					$item_id = $thisitem[id] ;
					$bids = mysql_query("SELECT * FROM bids WHERE item_id=$item_id ORDER BY bid_amount DESC LIMIT 1") ;
					if (mysql_num_rows($bids) > 0){
						while ($thisbid = mysql_fetch_array($bids)){
							$current_bid = $thisbid[bid_amount] ;
						}
					}
				//-------/end get current bid ----------
				echo "<font color=\"#666666\" face=\"sans-serif\">Retail value: $price<br>Opening bid: $ ".number_format($thisitem[opening_bid])."<br><font color=\"#ff000\"><b>Current bid: \$ ".number_format($current_bid)."</b></font></font><br>" ;
	
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
		
		
	?>




<?php				
	echo "</body></html>";
?>
