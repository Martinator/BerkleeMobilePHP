<?php require_once('../_lib/_db.php') ; ?>

	<html>
	<head>

	
	<link rel="stylesheet" type="text/css" href="../_mobile.css">	
	
	</head>
	
	<body background="../mobile_files/bg-tile-purple.jpg">
	<center>
	



	<table border="0" width="95%">
	<tr>
	<td><img src="../mobile_files/login-header2.jpg" border="0"></td>
	</tr>
	<tr>
	<td><br><br></td>
	</tr>
	<tr>
	<td>
	<?php

		$submission = htmlentities($_POST['submission'],ENT_QUOTES) ;
		$addnew = htmlentities($_POST['addnew'],ENT_QUOTES) ;

		$fname = htmlentities($_POST['fname'],ENT_QUOTES) ;
		$lname = htmlentities($_POST['lname'],ENT_QUOTES) ;
		
		if ($lname){
			$lname_array = explode(" ", $lname);		
			foreach ($lname_array as $value) {
				$lname_search .= "lname LIKE '%$value%' OR " ;
			}
			$lname_search = substr("$lname_search", 0, -4) ;
		}
		
		$query_string = $lname_search ;

		if ($fname){
			$fname_array = explode(" ", $fname);		
			foreach ($fname_array as $value) {
				$fname_search .= "fname LIKE '%$value%' OR " ;
			}
			$fname_search = substr("$fname_search", 0, -4) ;
			$fname_search .= $conj ;

			if ($lname_search){
				$query_string = $lname_search . " OR " . $fname_search ;
			} else {
				$query_string = $fname_search ;
			}
		}

		// echo "query_string is $query_string<br>" ;

		if ($submission && !$addnew){
			$result = mysql_query("SELECT people.*,salutations.salutation AS salutation_text FROM people LEFT JOIN salutations ON people.salutation=salutations.id WHERE $query_string", $db) ;
	
			echo "<table border=\"0\" align=\"center\">" ;
			echo "<tr><td align=\"center\" colspan=\"4\"><font color=\"#ffffff\"><u>Which guest are you looking for?</u></font><br><br></td></tr>" ;
			if (mysql_num_rows($result) > 0){
				while ($thisperson = mysql_fetch_array($result)){
					echo "<tr>";
					echo "<td align=\"left\" class=\"bodytext\">&nbsp;</td>";
					$fname = ($thisperson[fname]) ? $thisperson[fname] : $thisperson[salutation_text] ;
					$mname = $thisperson[mname] ;
					$lname = $thisperson[lname] ;
					$name = $fname . " " ;
					if ($mname){
						$name .= $mname . " " . $lname ;
					} else {
						$name .= $lname . " " ;
					}
					if ($inv_fname){
						$name .= "<br>($inv_fname)" ;
					} else {
						$donothing = "" ;
					}
					if ($thisperson[job_title]){
						$job_title = "<br>$thisperson[job_title]" ;
					} else {
						$job_title = "" ;
					}
					if ($thisperson[company]){
						$company = "<br>$thisperson[company]" ;
					} else {
						$company = "" ;
					}
					$text = $thisperson[notes] ;
					$phone = $thisperson[phone1] ;
					echo "<td align=\"left\" valign=\"top\" class=\"bodytext\"><b><a href=\"../demo_mobile_home.php?bidder_id=$thisperson[bidder_id]&docent=1\"><font color=\"#ffcc66\">$name</font></a></b>$job_title<br>$company<br>$phone</td>" ;
					echo "<td align=\"left\" class=\"bodytext\">&nbsp;</td>";
					$date = $thisperson[date_entered] ;
					$who = $thisperson[who_entered] ;
					echo "<td align=\"right\" class=\"bodytext\">Bidder <font color=\"#ffffff\">&#35;$thisperson[bidder_id]</font></td>";
					echo "</tr>";
					echo "<tr><td colspan=\"4\"><hr width=\"80%\"></td></tr>" ;
				}
			} else { 
				if ($submission){
					echo "<tr><td align=\"left\" class=\"bodytext\">No Results.  Please widen your search.</td></tr>" ;
				}
			}
			echo "</table>" ;
		}
		

	?>
	
	<!-- ENTER NEW PERSON FORM -->
		<?php
			if (!$addnew && $submission){
		?>
				<center>
				<form action="index.php" method="POST">
				<font face="arial,helvetica,sans-serif" color="#ffffff" size="4">Add a new guest<br><br>
				<input type="hidden" name="submission" value="1">
				<input type="hidden" name="addnew" value="1">
				<table border="0">
				<tr><td align="left"><font face="arial,helvetica,sans-serif" color="#ffffff" size="4">Name: </td><td><input type="text" name="fname"> <input type="text" name="lname"></font></td></tr>
				<tr><td align="left"><font face="arial,helvetica,sans-serif" color="#ffffff" size="4">Phone: </td><td><input type="text" name="phone"></font></td></tr>
				<tr><td align="left"><font face="arial,helvetica,sans-serif" color="#ffffff" size="4">Email: </td><td><input type="text" name="email"></font></td></tr>
				</table>
				<input type="submit" value="Add Person">
				</font>
				</form>
				</center>
		<?php
			}
		?>
	<!-- /END NEW PERSON FORM -->
	
	
	<!-- NEW PERSON DISPLAY -->
	<?php
		
		if ($addnew && $submission){
			// CREATE NEW PERSON, DISPLAY INFO AND BIDDER CODE, WITH LOGIN LINK TO HOME SCREEN
			$fname = htmlentities($_POST['fname'],ENT_QUOTES) ;
			$lname = htmlentities($_POST['lname'],ENT_QUOTES) ;
			$phone1 = htmlentities($_POST['phone'],ENT_QUOTES) ;
			$email = htmlentities($_POST['email'],ENT_QUOTES) ;
			$is_item_contact = 0 ;
			$is_attending = 1 ;
			$has_arrived = 1 ;
			$is_paid = 1 ;

			//------ CREATE BIDDER CODE -------
			include('../IMS/_bidder_key.php') ;	
			$bidder_id = get_bidder_id(6) ;
			$thefolks = mysql_query("SELECT bidder_id FROM people WHERE bidder_id='$bidder_id'") ;	
			if (mysql_num_rows($thefolks) > 0){
				// bidder_id is a dupe.
				$i = 0 ;
				while ($i == 0){
					$new_bidder_id = get_bidder_id(4) ;
					$thefolks = mysql_query("SELECT bidder_id FROM people WHERE bidder_id='$new_bidder_id'") ;	
					if (mysql_num_rows($thefolks) > 0){
						// bidder_id is a dupe.  Create & assign a new one.
						$i = 0 ;
					} else {
						// bidder_id is NOT a dupe. continue.
						$i = 1 ;
						$bidder_id = $new_bidder_id ;
					}			
				}
			}
			//---------------------------------


			$result = mysql_query("INSERT people (fname,lname,phone1,email,is_item_contact,is_attending,has_arrived,is_paid,date_modified,bidder_id) VALUES ('$fname','$lname','$phone1','$email','$is_item_contact','$is_attending','$has_arrived','$is_paid','$todaynum','$bidder_id')") ;

			echo "<table align=\"center\">" ;
			echo "<tr><td align=\"left\" valign=\"top\" class=\"bodytext\"><b><a href=\"../demo_mobile_home.php?bidder_id=$bidder_id&docent=1\"><font color=\"#ffcc66\">$fname $lname</font></a> - Start bidding!</b></td></tr>" ;
			echo "</table>" ;
		}
		
	?>
	<!-- /END NEW PERSON DISPLAY -->
	
	
	</td>
	</tr>
	<?php
		if (!$submission){
	?>
	<tr>
	<td align="center">
	<form action="index.php" method="POST">
	<font face="arial,helvetica,sans-serif" color="#ffffff" size="6">(Always help the guest)<br><br>
	<input type="hidden" name="submission" value="1">
	Name: <input type="text" name="fname"> <input type="text" name="lname"><br><br>
	<input type="submit" value="Find Guest">
	</font>
	</form>
	</td>
	</tr>
	<?php
		}
	?>
	</table>
	
	</center>



<?php				
	echo "</body></html>";
?>
