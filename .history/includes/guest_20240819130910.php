<?php

require_once(LIB_PATH.DS.'database.php');

class Guest {
    
    protected static $tbl_name = "tblguest";
    
    // Properties that correspond to database fields
    public $G_FNAME;
    public $G_LNAME;
    public $G_CITY;
    public $G_ADDRESS;
    public $G_PHONE;
    public $G_NATIONALITY;
    public $G_UNAME;
    public $G_PASS;
    public $LOCATION;

    // Retrieve all field names from the table
    function db_fields() {
        global $mydb;
        return $mydb->getFieldsOnOneTable(self::$tbl_name);
    }

    // List all guests
    function listOfguest() {
        global $mydb;
        $mydb->setQuery("SELECT * FROM ".self::$tbl_name);
        $cur = $mydb->loadResultList();
        return $cur;
    }

    // Retrieve a single guest by ID
    function single_guest($id=0) {
        global $mydb;
        $mydb->setQuery("SELECT * FROM ".self::$tbl_name." WHERE GUESTID = {$id} LIMIT 1");
        $cur = $mydb->loadSingleResult();
        return $cur;
    }

    // Find all guests by phone or email
    function find_all_guest($phone="", $email="") {
        global $mydb;
        $email = $mydb->escape_value($email);
        $phone = $mydb->escape_value($phone);
        $mydb->setQuery("SELECT * FROM ".self::$tbl_name." WHERE G_PHONE = '{$phone}' OR G_UNAME = '{$email}'");
        $cur = $mydb->executeQuery();
        $row_count = $mydb->num_rows($cur); // Get the number of rows
        return $row_count;
    }

    // Guest login function
    static function guest_login($email="", $pass="") {
        global $mydb;
        $email = $mydb->escape_value($email);
        $pass = $mydb->escape_value($pass);
        $mydb->setQuery("SELECT * FROM ".self::$tbl_name." WHERE G_UNAME = '{$email}' AND G_PASS = '{$pass}'");
        $cur = $mydb->executeQuery();
        if ($cur == false) {
            die(mysqli_error($mydb->conn));
        }
        $row_count = $mydb->num_rows($cur); // Get the number of rows
        if ($row_count == 1) {
            $found_user = $mydb->loadSingleResult();
            $_SESSION['GUESTID'] = $found_user->GUESTID;
            $_SESSION['name'] = $found_user->G_FNAME;
            $_SESSION['last'] = $found_user->G_LNAME;
            $_SESSION['address'] = $found_user->G_ADDRESS;
            $_SESSION['phone'] = $found_user->G_PHONE;
            $_SESSION['username'] = $found_user->G_UNAME;
            $_SESSION['pass'] = $found_user->G_PASS;
            return true;
        } else {
            return false;
        }
    }

    // Create a new Guest object from database record
    static function instantiate($record) {
        $object = new self;
        foreach ($record as $attribute => $value) {
            if ($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

    // Check if the attribute exists in the database fields
    private function has_attribute($attribute) {
        return array_key_exists($attribute, $this->attributes());
    }

    // Get all attributes of the object
    protected function attributes() {
        global $mydb;
        $attributes = array();
        foreach ($this->db_fields() as $field) {
            if (property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }

    // Sanitize attributes before database operations
    protected function sanitized_attributes() {
        global $mydb;
        $clean_attributes = array();
        foreach ($this->attributes() as $key => $value) {
            $clean_attributes[$key] = $mydb->escape_value($value);
        }
        return $clean_attributes;
    }

    // Save a guest record (create or update)
    public function save() {
        return isset($this->GUESTID) ? $this->update() : $this->create();
    }

    // Create a new guest record
    public function create() {
        global $mydb;
        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO ".self::$tbl_name." (";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        $mydb->setQuery($sql);
        if ($mydb->executeQuery()) {
            $this->GUESTID = $mydb->insert_id(); //errot of undefined property '$GUESTID'
            return true;
        } else {
            return false;
        }
    }

    // Update an existing guest record
    public function update($id=0) {
        global $mydb;
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = array();
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }
        $sql = "UPDATE ".self::$tbl_name." SET ";
        $sql .= join(", ", $attribute_pairs);
        $sql .= " WHERE GUESTID = ". $id;
        $mydb->setQuery($sql);
        if (!$mydb->executeQuery()) return false;
        return true;
    }

    // Delete a guest record
    public function delete($id=0) {
        global $mydb;
        $sql = "DELETE FROM ".self::$tbl_name;
        $sql .= " WHERE GUESTID = ". $id;
        $sql .= " LIMIT 1";
        $mydb->setQuery($sql);
        if (!$mydb->executeQuery()) return false;
        return true;
    }
}
?>
