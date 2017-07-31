<?php
class Database {
	
	// Public Variable
	public $result;
	public $error = false;
	public $errorMsg = '';
	
	// Private Variables
	private $mysqli;
	
	function __construct(){
		// Connect to Server
		$this->mysqli =  new mysqli('host', 'username', 'password', 'dbname');
		if($this->mysqli->connect_error){
			die('Connect Error (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
		}
	}
	
	public function query($query){
		if(isset($this->mysqli)){
			// What type of query are we doing?
			$returnResult = array('SELECT', 'SHOW', 'DESCRIBE', 'EXPLAIN');
			$substring = substr($query, 0, 20);
			$exploded = explode(' ', $substring);

			if(in_array($exploded[0], $returnResult)){
				// Query With Result
				if($this->result = $this->mysqli->query($query)){
					// Successful Query
				}else{
					// Query Error
					$this->error = true;
				}
			}else{
				// True False Query
				if($this->mysqli->query($query) === true){
					// Successful Query
				}else{
					// Query Error
					$this->error = true;
				}
			}
		}
	}
	
	public function escape($string){
		$escaped = $this->mysqli->real_escape_string($string);
		return $escaped;
	}
	
	public function resultArray(){
		$array = $this->result->fetch_array(MYSQLI_ASSOC);
		return $array;
	}
	
	public function singleResult($key){
		$array = $this->result->fetch_array(MYSQLI_ASSOC);
		return $array[$key];
	}
}
?>