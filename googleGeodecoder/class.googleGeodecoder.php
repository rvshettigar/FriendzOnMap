<?php
	/**
	* Google Geocoding
	* 
	* This class implements function to use Google Geodecoding API 
	* to convert an address string into latitude and longitude.
	*
	* 
	* @class        googleGeodecoder
	* @author       Rakshith Shettigar <rvshettigar.89@gmail.com>
	*/
	require 'handleDB.php';
	class googleGeodecoder {
	 	
		/**
		* Reads an URL to a string
		* @param string $url The URL to read from
		* @return string The URL content
		*/

		private function get_data($url){

		  $ch = curl_init();
		  $timeout = 5;
		  curl_setopt($ch,CURLOPT_URL,$url);
		  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		  curl_setopt($ch, CURLOPT_HEADER, 0);
		  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		  //curl_setopt($ch, CURLOPT_POST, TRUE);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		  curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
		  $data = curl_exec($ch);
		  curl_close($ch);
		  return $data;
		}
		/**
		* Get Latitude/Longitude/Altitude based on an address
		* @param string $address The address for converting into coordinates
		* @return array An array containing Latitude/Longitude/Altitude data
		*/
		public function getCoordinates($location){
			if(!isset($location['name'])){
				return array('lat' => 0, 'lng' => 0);
			}
			$address = urlencode($location['name']);
			$loc_id = $location['id'];
			$dbObj = new handleDB('tunnel.pagodabox.com:3306','kandace','iKvVqxny');
			$result = $dbObj -> checkAddress('geocoded_address', $location);
			if ($result){
				return array('lat' => $result['lat'], 'lng' => $result['lng']);
			}else{
				$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&sensor=false';
			 	$data = $this->get_data($url);
				$jsondata = json_decode($data,true);

				if($jsondata){
					if(strcmp($jsondata['status'],'OK')==0){
						$location['lat']= $jsondata['results'][0]['geometry']['location']['lat'];
						$location['lng']= $jsondata['results'][0]['geometry']['location']['lng'];
						$dbObj -> insertData('geocoded_address',$location);
						return array('lat' => $jsondata['results'][0]['geometry']['location']['lat'], 'lng' => $jsondata['results'][0]['geometry']['location']['lng']);

					}
				}
				//return default data
				return array('lat' => 0, 'lng' => 0);
				}
		 	
		}

		/**
		* Get the current locations for friends.
		*/
		
		public function getFriendsLocation($access_token){
			$url = "https://graph.facebook.com/fql?q=SELECT uid,name,pic_square,profile_url,current_location FROM user where uid in (select uid2 from friend WHERE uid1=me())&access_token=" . $access_token;
			//return $url;
			$data = $this -> get_data($url);
			//return $data;
			$jsondata = json_decode($data,true);
			return $jsondata;
		}

	}; //end class
?>
