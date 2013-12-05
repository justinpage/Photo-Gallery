<?php 
require_once(LIB_PATH.DS."config.php");

class MySQLDatabase {
	private $connection;
	public $last_query;

	function __construct() {
		$this->open_connection();
	}

	public function open_connection() {
 		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
 		if(mysqli_connect_error()) {
 			die("Database connection failed " . 
 				mysqli_connect_error() . " (" . mysqli_connect_errno() . ")"
 			);
 		}
 	}

 	public function close_connection() {
 		if($this->connection) {
 			mysqli_close($this->connection);
 			unset($this->connection);
 		}
 	}

 	public function query($sql) {
 		$this->last_query = $sql;
 		$result = mysqli_query($this->connection, $sql);
 		$this->confirm_query($result);
 		return $result;
 	}

 	public function escape_value($string) {
 		$escaped_string = mysqli_escape_string($this->connection, $string);
 		return $escaped_string;
 	}

 	public function fetch_assoc_array($result_set) {
 		return mysqli_fetch_assoc($result_set);
 	}

 	public function num_rows($result_set) {
 		return mysqli_num_rows($result_set);
 	}

 	public function insert_id() {
 		// get the last id inserted over the current db connection
 		return mysqli_insert_id($this->connection);
 	}

 	public function affected_rows() {
 		return mysqli_affected_rows($this->connection);
 	}

 	private function confirm_query($result) {
 		if(!$result) {
 			$output = "Database query failed " . mysqli_error() . "<br><br>";
 			$output .= "Last SQL query: " . $this->last_query;
 			die($output);
 		}
 	}
}

$database = new MySQLDatabase();
$db =& $database;

 ?>