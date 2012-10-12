<?php require_once('_lib/_db.php') ; ?>
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
	

		<div class="landing" data-theme="a" data-role="page">
		
			<div data-role="content">

				<img src="images/login-header2.jpg" class="logo" />



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
			echo "<ul data-role=\"listview\"  data-theme=\"c\" data-inset=\"true\"  data-divider-theme=\"a\">" ;
			echo "<li data-role=\"list-divider\">Which guest are you looking for?</li>" ;
			if (mysql_num_rows($result) > 0){
				while ($thisperson = mysql_fetch_array($result)){
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
						$name .= "($inv_fname)" ;
					} else {
						$donothing = "" ;
					}
					if ($thisperson[job_title]){
						$job_title = "$thisperson[job_title]" ;
					} else {
						$job_title = "" ;
					}
					if ($thisperson[company]){
						$company = "$thisperson[company]" ;
					} else {
						$company = "" ;
					}
					$text = $thisperson[notes] ;
					$phone = $thisperson[phone1] ;
					echo "<li><a href=\"demo_mobile_home.php?bidder_id=$thisperson[bidder_id]&docent=1\">";
					echo "<h3>$name</h3><p>$job_title</p><p>$company</p><p>$phone</p>" ;
					$date = $thisperson[date_entered] ;
					$who = $thisperson[who_entered] ;
					echo "<p>Bidder &#35;$thisperson[bidder_id]</p>";
					echo "</a></li>";

				}
			} else { 
				if ($submission){
					echo "<h2>No Results.  Please widen your search.</h2>" ;
				}
			}
			echo "</ul>" ;
		}
		

	?>
	
	<!-- ENTER NEW PERSON FORM -->
		<?php
			if (!$addnew && $submission){
		?>
		<form action="demo_mobile_login-docent.php" method="POST">
				<input type="hidden" name="submission" value="1">
				<input type="hidden" name="addnew" value="1">
				<div data-role="fieldcontain" >
					<label for="fname">First Name</label>
					<input type="text" name="fname" id="fname" placeholder="First Name"/>
				</div>
				<div data-role="fieldcontain" >
					<label for="lname">Last Name</label>
					<input type="text" name="lname" id="lname" placeholder="Last Name"/>
				</div>
				<div data-role="fieldcontain" >
					<label for="phone">Phone</label>
					<input type="tel" name="phone" id="phone" placeholder="Phone"/>
				</div>
				<div data-role="fieldcontain" >
					<label for="email">Email</label>
					<input type="email" name="email" id="email" placeholder="Email"/>
				</div>
				<input type="submit" value="Add Person" data-theme="a"/>
		</form>

	
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
			include('IMS/_bidder_key.php') ;	
			$bidder_id = get_bidder_id(4) ;
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

			echo "<ul data-role=\"listview\"  data-theme=\"c\" data-inset=\"true\"  data-divider-theme=\"a\">" ;
			echo "<li data-role=\"list-divider\">Which guest are you looking for?</li>" ;
			echo "<li><a href=\"demo_mobile_home.php?bidder_id=$bidder_id&docent=1\"><h3>$fname $lname</h3><p>Start bidding!</p></a></li>" ;
			echo "</ul>" ;
		}
		
	?>
	<!-- /END NEW PERSON DISPLAY -->
	
	
	</td>
	</tr>
	<?php
		if (!$submission){
	?>
				<form action="demo_mobile_login-docent.php" method="POST">
					<h2>(Always help the guest)</h2>
					<input type="hidden" name="submission" value="1">
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="fname">First Name</label>
						<input type="text" name="fname" id="fname" data-theme="b" placeholder="First Name" />
					</div>
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="lname">Last Name</label>
						<input type="text" name="lname" id="lname" data-theme="b"  placeholder="Last Name" />
					</div>
					<input type="submit" value="Find Guest" data-theme="b">
				</form>

	<?php
		}
	?>

	
		</div><!-- /content -->
	</div><!-- /page -->



<?php		

	echo "</body></html>";
?>
