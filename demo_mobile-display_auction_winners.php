
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



<!-- --------------------------------------------------------------------------------------------------------- -->

		
		<center><font size="+3"><b><span class="bodyheader">Silent Auction Winners</span></b></font></center>
		<br><br>
	
	
	<?php
	
	//	today's date
		$todaynum = date("Y-m-d") ;
	//	echo "Today is $todaynum <p>" ;
		
		
		// connect to DB
		require_once('_lib/_db.php') ;
	//	ORDER results by chosen column
		
		$startfrom = htmlentities($_GET['startfrom'],ENT_QUOTES) ;
		if ($startfrom<1){
			$startfrom = 0 ;
		} else {
			$donothing  = 1 ;
		}
	
		// echo "start from: $startfrom<p>" ;
		
	//	$startfrom = 1 ;
		$themax = 9 ;
		$result = mysql_query("SELECT items.id,items.title,items.winner_id,items.catalogue_number,people.id,people.fname,people.lname,salutations.salutation AS salutation_text FROM items LEFT JOIN people ON items.winner_id=people.id LEFT JOIN salutations ON people.salutation=salutations.id WHERE items.winner_id>0 ORDER BY people.lname,people.fname LIMIT $startfrom,$themax", $db) ;
	
		if (mysql_num_rows($result) > 0){
			echo "<table border=\"0\" align=\"center\">" ;
			echo "<tr><td class=\"bodytext\">&nbsp;</td><td class=\"bodytext\"><b>Winner</b></td><td>&nbsp;</td><td class=\"bodytext\"><b>Item(s) Won</b></td></tr>" ;
			echo "<tr><td class=\"bodytext\">&nbsp;</td><td class=\"bodytext\" colspan=\"4\"><hr></td></tr>" ;
			$lastperson = "" ;
			while ($thisitem = mysql_fetch_array($result)){
				if ($thisitem[winner_id]){
					echo "<tr>";
					echo "<td align=\"left\" class=\"bodytext\">&nbsp;</td>";
					echo "<td align=\"left\" valign=\"top\" class=\"bodytext\">" ;
					if ($thisitem[winner_id] == $lastperson){
						echo "&nbsp;" ;
					} else {
						$fname = ($thisitem[fname]) ? $thisitem[fname] : $thisitem[salutation_text] ;
						echo "<b><a href=\"check_out_invoice.php?id=$thisitem[winner_id]\">$fname $thisitem[lname]</a></b>" ;
					}
					echo "</td>" ;
					echo "<td>&nbsp;</td>" ;
					echo "<td align=\"left\" class=\"bodytext\">$thisitem[title]</td>" ;
					echo "</tr>";
					echo "<tr><td class=\"bodytext\" colspan=\"10\">&nbsp;</td></tr>" ;
					$lastperson = $thisitem[winner_id] ;
				} else {
					$donothing = "" ;
				}
			}
			$startfrom = $startfrom + 9 ;
			echo "<meta http-equiv=\"Refresh\" content=\"10;url=demo_mobile-display_auction_winners.php?startfrom=$startfrom\">" ;
			$nextlink = "demo_mobile-display_auction_winners.php?startfrom=$startfrom" ;
		} else { 
			echo "<center>Retrieving winners...</center>" ;
			$startfrom = 0 ;
			echo "<meta http-equiv=\"Refresh\" content=\"2;url=demo_mobile-display_auction_winners.php?startfrom=$startfrom\">" ;
			$nextlink = "demo_mobile-display_auction_winners.php?startfrom=$startfrom" ;
		}
		echo "</table>" ;
		
		echo "<center><i><a href=\"$nextlink\">&#187; NEXT &#187;</a></i></center>" ;
	
	
	
	?>

<!-- --------------------------------------------------------------------------------------------------------- -->

</body>
</html>