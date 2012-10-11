<?php

	require('config.php') ;
	@$db = mysql_connect(SQL_HOST, SQL_USERNAME, SQL_PASSWORD) ;
	mysql_select_db("gala", $db) ;
	
	$gala_year = "2012" ;
	$gala_day = "Saturday" ;
	$gala_date = "October 13, ".$gala_year ;
	$gala_ordinal = "Eighteenth" ;
	$gala_ordinal_num = "18<sup><font size=-1>th</font></sup>" ;

	$today = date("F j, Y") ;

	$IRS = "As required by IRS regulations, this receipt will confirm that no goods or services were provided in consideration for this gift." ;
	$fair_market = "<i>P.S. We estimate the fair market value of this event to be $75 per guest. Your contribution in excess of this amount is tax-deductible in accordance with IRS regulations.</i>" ;

	$todaynum = date("Y-m-d") ;
