<?php
	//include our class
	require_once(dirname(__FILE__) . '/class.googleGeodecoder.php'); 

	//require('header.php');
	
	//init our object
	$obj = new googleGeodecoder();

	//get the access token

	//$access_token = $facebook -> getAccessToken();

	//echo $access_token;
	
	//get coordinates and print the debug info
	$address = 'Los Angeles, CA';
	print 'For our "' . $address . '" we have these data : ';
	print '<pre>';
	print_r($obj->getCoordinates($address ));
	print '<pre>';
?>