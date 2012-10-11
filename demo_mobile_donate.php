<?php require_once('_lib/_db.php') ; ?>

<?php require_once('_bidder_info.php') ; ?>

	<html>
	<head>
<meta http-equiv="content-type" content="text/html; charset=us-ascii">
	
		<title>Berklee Mobile Bidder</title>

		<link rel="stylesheet" type="text/css" href="_mobile.css">	
	
	
	</head>
	<body background="mobile_files/bg-tile-beige.jpg">
	<center>

	<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td align="left"><a href="demo_mobile_home.php">Home</a></td>
	<td align="right"><a href="demo_mobile_donate.php">Refresh</a></td>
	</tr>
	</table>


	<table border="0" width="480" cellspacing="0" cellpadding="0">
	<tr>
	<td><a href="demo_mobile_home.php"><img src="mobile_files/gala-logo.jpg"></a></td>
	<td><font face="sans-serif" color="#666666" size="4"><b>Bidder<br><br><a href="demo_mobile_home.php"><font face="sans-serif" color="#666666"><?php echo $name ; ?></font></a></b></font></td>
	</tr>
	</table>

	<?php
	
		$submission = htmlentities($_POST['submission'],ENT_QUOTES) ;
		$donor_id = htmlentities($_POST['donor_id'],ENT_QUOTES) ;
		$half_scholarship = htmlentities($_POST['half_scholarship'],ENT_QUOTES) ;
		$full_scholarship = htmlentities($_POST['full_scholarship'],ENT_QUOTES) ;
		$other_amount = htmlentities($_POST['other_amount'],ENT_QUOTES) ;
		$other_amount = str_replace(",","",$other_amount) ;
		

		$date = date("Y-m-j") ;
		$time = date("H:i:s") ;
		
		//if ($submission){
		//	echo "<meta http-equiv=\"Refresh\" content=\"5;url=demo_mobile_home.php\">" ;
		//}

		echo "<table border=\"0\" width=\"400\">" ;
		if ($half_scholarship > 0){
			$record_donation = mysql_query("INSERT donations (bidder_user_id,half_scholarship,date,time) VALUES ('$bidder_user_id','$half_scholarship','$date','$time')") ;
			echo "<tr><td><font face=\"sans-serif\" color=\"#666666\">Half scholarships (\$2,500) &nbsp;&nbsp;x&nbsp;&nbsp; </font></td><td><font face=\"sans-serif\" color=\"#666666\">$half_scholarship &nbsp;&nbsp;=&nbsp;&nbsp; </font></td><td align=\"right\"><font face=\"sans-serif\" color=\"#666666\">\$" . number_format($half_scholarship * 2500) . "</font></td></tr>" ;
		}
		if ($full_scholarship > 0){
			$record_donation = mysql_query("INSERT donations (bidder_user_id,full_scholarship,date,time) VALUES ('$bidder_user_id','$full_scholarship','$date','$time')") ;
			echo "<tr><td><font face=\"sans-serif\" color=\"#666666\">Tuition scholarships (\$5,000) &nbsp;&nbsp;x&nbsp;&nbsp; </font></td><td><font face=\"sans-serif\" color=\"#666666\">$full_scholarship &nbsp;&nbsp;=&nbsp;&nbsp; </font></td><td align=\"right\"><font face=\"sans-serif\" color=\"#666666\">\$" . number_format($full_scholarship * 5000) . "</font></td></tr>" ;
		}
		if ($other_amount > 0){
			$record_donation = mysql_query("INSERT donations (bidder_user_id,other_amount,date,time) VALUES ('$bidder_user_id','$other_amount','$date','$time')") ;
			echo "<tr><td colspan=\"2\"><font face=\"sans-serif\" color=\"#666666\">Other donation: </font></td><td align=\"right\"><font face=\"sans-serif\" color=\"#666666\">\$". number_format($other_amount) . "</font></td></tr>" ;
		}
		echo "</table>" ;
	
	?>


	<?php
		if ($submission != 1){
	?>
		<font face="sans-serif" color="#666666"><b>Fund-A-Future</b>: Support these worthy initiatives at Berklee:</font>
		<br><br>
		<form action="demo_mobile_donate.php" method="POST">
		<input type="hidden" name="submission" value="1">
		<input type="hidden" name="donor_id" value="<?php echo $bidder_user_id ; ?>">
		
		<table border="0" cellspacing="0" cellpadding="5" width="500">
		<tr>
		<td><font face="sans-serif" color="#666666"> Tuition &#43; Room &#38; Board scholarship </font></td>
		<td>
			<select name="half_scholarship">
			<option value="">--</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
		</td>
		<td>
			<font face="sans-serif" color="#666666">
			&nbsp;&nbsp; x &nbsp;&nbsp;
			$8,000 each</font>
		</td>
		</tr>
		<tr>
		<td><font face="sans-serif" color="#666666"> Tuition-only scholarship </font></td>
		<td>
			<select name="full_scholarship">
			<option value="">--</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
		</td>
		<td>
			<font face="sans-serif" color="#666666">
			&nbsp;&nbsp; x &nbsp;&nbsp;
			$5,000 each</font>
		</td>
		</tr>
		<tr>
		<td><font face="sans-serif" color="#666666">Other amount</font></td>
		<td>&nbsp;</td>
		<td align="right"><font face="sans-serif" color="#666666">$ <input type="number" name="other_amount" size="7"></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
		<td align="center" colspan="3"><input type="submit" value="DONATE!"></td>
		</tr>
		</table>
		<form>
	<?php
		} else {
			echo "<font face=\"sans-serif\" color=\"#666666\"><br><br>Thank you for your generous donations to Fund the Futures of Boston's inner-city youth!</font><br>" ;
			echo "<meta http-equiv=\"Refresh\" content=\"5;url=demo_mobile_home.php\">" ;
		}
	?>


<?php				
	echo "</center></body></html>";
?>
