	<?php
		// TIME, COUNTDOWN, SHUTOFF
		
		$time = date(Hi) ;
		
		//echo "time is $time<br>" ;
		
		if ($time >= 2230){
			//echo "It's after 10:30.";
			$auction_warning = "<p>The auction is now CLOSED - retrieving results</p>" ;
			$auction_closed = 1 ;
		} else {
			//echo "It's not yet 10:30." ;
			$tminus = 2230-$time ;
			//echo "Tminus is $tminus<br>" ;
			if ($tminus < 15){
				$auction_warning = "<p>The auction closes in $tminus minutes.  Get your bids in now!</p>" ;
			}
		}
		
		// $auction_closed = 1 ;
	?>