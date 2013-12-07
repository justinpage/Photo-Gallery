<?php
// If its going to need the database, then it's
// probably smart to require it before we start. 
// if already required, doesn't include it twice -good practice
require_once(LIB_PATH.DS.'database.php');

class  DatabaseObject {


	// Static bindings available - watch 06 13 for proper bindings using static

	// Common Database Methods
	public static function find_all() {
		global $database;
		$result_set = static::find_by_sql("SELECT * FROM " . static::$table_name);
		return $result_set;
	}

	public static function find_by_id($id=0) {
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM " . static::$table_name . " WHERE id={$database->escape_value($id)} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	} 

	public static function find_by_sql($sql="") {
		 global $database;
		 $result_set = $database->query($sql);
		 $object_array = array();
		 while($row = $database->fetch_assoc_array($result_set)) {
		 	$object_array[] = static::instantiate($row);
		 }

		 return $object_array;

	}

	private static function instantiate($record) {
		// could check that $exists and in an array
		// simple, long-form approoach:
		$class_name = get_called_class();
		$object = new $class_name;
		// $object->id 		= $record['id'];
		// $object->username 	= $record['username']; 
		// $object->password 	= $record['password'];
		// $object->first_name = $record['first_name'];
		// $object->last_name  = $record['last_name'];

		// More dynamic, short-form approach:
		foreach ($record as $attribute => $value) {
			if($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}
		return $object;
	}

	private function has_attribute($attribute) {
		// get_object_vars returns an associative array with all attribures
		// including private ones! as the keys and their current values as the value
		$object_vars = $this->attributes();
		// We don't care about the value, we just want to know if the key exists
		// will return true or false
		return array_key_exists($attribute, $object_vars);
	}

	protected function attributes() {
		// return an array of attribute keys and their values
		$attributes = array();
		foreach ($this::$db_fields as $field) {
			if(property_exists($this, $field)) {
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}

	protected function sanitized_attributes() {
		global $database;
		$clean_attributes = array();
		// sanitize the values before submitting
		// Note: does not alter the actual value of each attribute
		foreach ($this->attributes() as $key => $value) {
			$clean_attributes[$key] = $database->escape_value($value);
		}

		return $clean_attributes;
	}


	public function save() {
		// A new record won't have an id yet
		return isset($this->id) ? $this->update() : $this->create();
	}
	
	public function create() {
	
		global $database;
		// Don't forget your SQL syntax and good habits:
		// -INSERT INTO table(key, key) VALUES ('value', 'value')
		// -single-quotes around all values
		// -escape all valuues to prevent SQL injection
		$attributes = $this->sanitized_attributes();

		$sql = "INSERT INTO ".$this::$table_name." (";
	    $sql .= join(", ", array_keys($attributes));
	    $sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
	    if($database->query($sql)) {
		    $this->id = $database->insert_id();
		    return true;
	  }
	  return false;
	}

	public function update() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach ($attributes as $key => $value) {
			$attribute_pairs[] = "{$keys}='{$value}'";
		}

		$sql = "UPDATE ".$this::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=". $database->escape_value($this->id);
	    $database->query($sql);
	    return ($database->affected_rows() == 1) ? true : false;
	}

	public function delete() {
		global $database;
		$sql = "DELETE FROM ".$this::$table_name;
		$sql .= " WHERE id=" . $database->escape_value($this->id);
		$sql .= " LIMIT 1";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}
}



 ?>