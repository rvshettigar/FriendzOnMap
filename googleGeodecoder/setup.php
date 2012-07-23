<?php
	$con = mysql_connect('tunnel.pagodabox.com:3306','kandace','iKvVqxny') ;
	if (!$con){
	  die('Could not connect: ' . mysql_error());
	}
	mysql_select_db('friendzDB', $con);
	$query = "create table geocoded_address( loc_id varchar(40), address varchar(40), lat double, lng double, primary key(loc_id) )";
	mysql_query($query);
	mysql_close($con);
?>
