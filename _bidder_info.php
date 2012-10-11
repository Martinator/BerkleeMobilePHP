<?php


	//$bidder_user_id = $_COOKIE["bidder_user_id"] ; 
	$bidder_user_id = htmlentities($_COOKIE["bidder_user_id"],ENT_QUOTES) ; 

	
	// echo "bidder_user_id is $bidder_user_id<br>" ;

	$people = mysql_query("SELECT * FROM people WHERE id='$bidder_user_id'") ;
	if (mysql_num_rows($people) > 0){
		while ($thiscat = mysql_fetch_array($people)){
			setcookie('bidder_user_id', $thiscat['id'], time()+21600);
			//echo '<!-- COOKIE: '.$_COOKIE['bidder_user_id'].', ID: '.$thiscat['id'].' -->';

			if ($thiscat[nickname]){
				$howdy = " ($thiscat[nickname]) " ;
			} else {
				$howdy = "" ;
			}
			if ($thiscat[mname]){
				$middle = " $thiscat[mname] " ;
			} else {
				$middle = "" ;
			}
			
			$fname = ($thiscat[fname]) ? $thiscat[fname] : $my_salutation ;
			$name = $fname . $howdy . $middle . " " . $thiscat[lname] ;
		}
	} else {
		//echo "got nobody" ;
	}
