<?php
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);
	//include our class
	require 'header.php';
	require_once(dirname(__FILE__) . '/googleGeodecoder/class.googleGeodecoder.php'); 
	require_once 'googleGeodecoder/handleDB.php';
	
	

	// if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['name']) && !empty($_POST['name'])){
	// 	$location['id'] = $_POST['id'];
	// 	$location['name'] = $_POST['name'];	
	// 	getLatLng($location);
	// }
	//getLatLng();
	
	//function getLatLng(){
		
		//init our object
		$obj = new googleGeodecoder();
		//get the friend location
		$fql = 'SELECT uid,name,pic_square,profile_url,current_location FROM user where uid in (select uid2 from friend WHERE uid1=me())';
		//$fql = "SELECT uid2 FROM friend WHERE uid1=me()";
		$friend_location = $facebook -> api(array(
	                                   'method' => 'fql.query',
	                                   'query' => $fql,
	                                 ));
		for ($i=0; $i< count($friend_location);$i++){
			if($friend_location[$i]['current_location']){
				$result = $obj -> getCoordinates($friend_location[$i]['current_location']);
				if ($result['lat'] !=0 and $result['lng'] !=0 ){
					$friend_location[$i]['current_location']['lat'] = $result['lat'];
					$friend_location[$i]['current_location']['lng'] = $result['lng'];
				}
			}
		}

		$jsonfriend = json_encode($friend_location);
		echo $jsonfriend;
		
	//}
	
	
?>