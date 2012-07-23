<?php
	//$con = mysql_connect('tunnel.pagodabox.com:3306','kandace','iKvVqxny') ;
	$con = new mysqli('tunnel.pagodabox.com','kandace','iKvVqxny','3306');
	if (!$con){
	  die('Could not connect: ' . mysql_error());
	}
	$con -> select_db('friendzDB');
	$query = "create table geocoded_address( loc_id varchar(40), address varchar(40), lat double, lng double, primary key(loc_id) )";
	$con->query($query);
	$con->close();
?>
