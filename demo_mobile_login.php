<? require_once('_lib/_db.php') ; ?>

	<html>
	<head>

	
		<link rel="stylesheet" type="text/css" href="_mobile.css">	
	
	</head>
	
	<body background="mobile_files/bg-tile-purple.jpg">
	<center>
	
	<table border="0" width="95%">
	<tr>
	<td><img src="mobile_files/login-header2.jpg" border="0"></td>
	</tr>
	<tr>
	<td><br><br></td>
	</tr>
	<tr>
	<td align="center">
	<form action="demo_mobile_home.php" method="POST">
	<font face="arial,helvetica,sans-serif" color="#ffffff" size="6">Please enter your bidder number:<br><br>
	<input type="number" name="bidder_id" size="16"><br><br>
	<input type="submit" value="Start bidding!">
	</font>
	</form>
	</td>
	</tr>
	</table>
	
	</center>



<?php				
	echo "</body></html>";
?>
