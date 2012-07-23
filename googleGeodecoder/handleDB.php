<?php
class handleDB{

	/*
	 *Holds the database connection variable
	 */
	private $dbCon;

	private $database = 'friendzDB';

	/*
	* Constructor
	*/

	public function __construct($host,$username, $password){
		$con = mysql_connect($host,$username,$password) ;
		if (!$con){
		  die('Could not connect: ' . mysql_error());
		}
		$this->dbCon = $con;
		mysql_select_db($this->database, $this-> dbCon);
	}


	/*
	* Inserting data into the database
	*/
	
	public function insertData($table, $data){
		$id = $data['id'];
		$name = $data['name'];
		$lat = $data['lat'];
		$lng = $data['lng'];
		
		$query = "INSERT into $table (loc_id,address,lat,lng) VALUES ('" . $id . "','" . $name . "',". $lat . "," . $lng. ")";
		mysql_query($query);

	}

	public function checkAddress($table, $data){
		if (!isset($data['id'])){
			return;
		}
		$id = $data['id'];
		$query = "SELECT * from $table WHERE loc_id = '" .$id. "'";
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){
			return $row;
		}
	}

	public function __destruct(){
		mysql_close($this -> dbCon);
	}	
}


?>
