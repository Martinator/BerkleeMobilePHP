<?php
	require_once('_lib/_db.php') ;
	
?>
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
		
		<div data-role="page" data-theme="a">
			<div data-role="header" data-position="fixed">
				<h1>>Bids of $10,000 and up</h1>

			</div><!-- /header -->
			<div data-role="content">

	<?php
	
		$allbigs = mysql_query("SELECT bids.*,people.fname,people.lname,items.title FROM bids LEFT JOIN people ON bids.bidder_user_id=people.id LEFT JOIN items ON bids.item_id=items.id WHERE bid_amount>'9999' ORDER BY bids.bidder_user_id,bids.bid_amount DESC") ;
		if (mysql_num_rows($allbigs) > 0){
			echo "<ul>" ;
			while ($thisbiggun = mysql_fetch_array($allbigs)){
				echo "<li>" ;
				echo "<h3>$thisbiggun[fname] $thisbiggun[lname]</h3><p>$thisbiggun[title]</p><p>\$".number_format($thisbiggun[bid_amount])."</p>" ;
				echo "</li>" ;
			}
			echo "</ul>" ;
		} else {
			echo "<h2>No monster bids found.</h2>" ;
		}
	

		echo "</div>" ; //close "content"
		echo "</div>" ; //close "page"
		echo "<meta http-equiv=\"Refresh\" content=\"15;url=demo_mobile_5-figure-check.php\">" ;
	?>



<?php				
	echo "</body></html>";
?>
