<?php
/**
* Description: This is a class for managing guest-related operations.
* Author: Joken Villanueva
* Date Created: Nov. 2, 2013
* Revised By: [Your Name]
*/

require_once(LIB_PATH . DS . 'database.php');

class Guest {

    protected static $tbl_name = "tblguest";

    // Get the fields of the guest table
    function db_fields() {
        global $mydb;
        return $mydb->getFieldsOnOneTable(self::$tbl_name);
    }

    // List all guests
    function listOfGuest() {
        global $mydb;
        $mydb->setQuery("SELECT * FROM " . self::$tbl_name);
        return $mydb->loadResultList();
    }

    // Get a single guest by ID
    function single_guest($id = 0) {
        global $mydb;
        $sql = "SELECT * FROM " . self::$tbl_name . " WHERE GUESTID = ? LIMIT 1";
        $stmt = $mydb->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Find guests by phone or email
    function find_all_guest($phone = "", $email = "") {
        global $mydb;
        $sql = "SELECT * FROM " . self::$tbl_name . " WHERE G_PHONE = ? OR G_EMAIL = ?";
        $stmt = $mydb->prepare($sql);
        $stmt->bind_param("ss", $phone, $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows;
    }

    // Guest login method
    static function guest_login($username = "", $password = "") {
        global $mydb;
        $sql = "SELECT * FROM " . self::$tbl_name . " WHERE G_UNAME = ? AND G_PASS = ? LIMIT 1";
        $stmt = $mydb->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $found_user = $result->fetch_assoc();
            $_SESSION['GUESTID'] = $found_user['GUESTID'];
            $_SESSION['name'] = $found_user['G_FNAME'];
            $_SESSION['last'] = $found_user['G_LNAME'];
            $_SESSION['address'] = $found_user['G_ADDRESS'];
            $_SESSION['phone'] = $found_user['G_PHONE'];
            $_SESSION['username'] = $found_user['G_UNAME'];
            $_SESSION['pass'] = $found_user['G_PASS'];
            return true;
        } else {
            return false;
        }
    }

    // Save guest (create or update based on ID)
    public function save() {
        return isset($this->id) ? $this->update() : $this->create();
    }

    // Create new guest record
    public function create() {
        global $mydb;
        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . self::$tbl_name . " (" . join(", ", array_keys($attributes)) . ") VALUES ('" . join("', '", array_values($attributes)) . "')";
        $mydb->setQuery($sql);
        if ($mydb->executeQuery()) {
            $this->id = $mydb->insert_id();
            return true;
        } else {
            return false;
        }
    }

    // Update guest record by ID
    public function update($id = 0) {
        global $mydb;
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = [];
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }
        $sql = "UPDATE " . self::$tbl_name . " SET " . join(", ", $attribute_pairs) . " WHERE GUESTID = ?";
        $stmt = $mydb->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Delete guest record by ID
    public function delete($id = 0) {
        global $mydb;
        $sql = "DELETE FROM " . self::$tbl_name . " WHERE GUESTID = ? LIMIT 1";
        $stmt = $mydb->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Instantiate object dynamically
    static function instantiate($record) {
        $object = new self;
        foreach ($record as $attribute => $value) {
            if ($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

    // Check if object has attribute
    private function has_attribute($attribute) {
        return array_key_exists($attribute, $this->attributes());
    }

    // Get object attributes
    protected function attributes() {
        $attributes = [];
        foreach ($this->db_fields() as $field) {
            if (property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }

    // Sanitize attributes before saving
    protected function sanitized_attributes() {
        global $mydb;
        $clean_attributes = [];
        foreach ($this->attributes() as $key => $value) {
            $clean_attributes[$key] = $mydb->escape_value($value);
        }
        return $clean_attributes;
    }
}
?>
