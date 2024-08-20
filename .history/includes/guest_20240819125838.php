class Guest {
    public $G_FNAME;
    public $G_LNAME;
    public $G_CITY;
    public $G_ADDRESS;
    public $G_PHONE;
    public $G_NATIONALITY;
    public $G_UNAME;
    public $G_PASS;
    public $LOCATION;

    // Example of a method to update guest details
    public function update($id) {
        global $mydb;
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = array();
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }
        $sql = "UPDATE ".self::$tbl_name." SET ";
        $sql .= join(", ", $attribute_pairs);
        $sql .= " WHERE GUESTID=". $id;
        $mydb->setQuery($sql);
        if (!$mydb->executeQuery()) return false;
    }

    // Sanitize attributes before updating
    private function sanitized_attributes() {
        global $mydb;
        $clean_attributes = array();
        foreach ($this->attributes() as $key => $value) {
            $clean_attributes[$key] = $mydb->escape_value($value);
        }
        return $clean_attributes;
    }

    // Get attributes from the database fields
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
}
