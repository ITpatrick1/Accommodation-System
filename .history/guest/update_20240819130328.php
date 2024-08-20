<?php
require_once("../includes/initialize.php");

// Define Guest class with necessary properties and methods
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

    protected static $tbl_name = "tblguest";

    function db_fields() {
        global $mydb;
        return $mydb->getFieldsOnOneTable(self::$tbl_name);
    }

    function update($id) {
        global $mydb;
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = array();
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }
        $sql = "UPDATE " . self::$tbl_name . " SET ";
        $sql .= join(", ", $attribute_pairs);
        $sql .= " WHERE GUESTID=" . $id;
        $mydb->setQuery($sql);
        if (!$mydb->executeQuery()) return false;
        return true;
    }

    private function sanitized_attributes() {
        global $mydb;
        $clean_attributes = array();
        foreach ($this->attributes() as $key => $value) {
            $clean_attributes[$key] = $mydb->escape_value($value);
        }
        return $clean_attributes;
    }

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

if (isset($_POST['submit'])) {
    $guest = new Guest();
    $guest->G_FNAME = $_POST['name'];
    $guest->G_LNAME = $_POST['last'];
    $guest->G_CITY = $_POST['city'];
    $guest->G_ADDRESS = $_POST['address'];
    $guest->G_PHONE = $_POST['phone'];
    $guest->G_NATIONALITY = $_POST['nationality'];
    $guest->G_UNAME = $_POST['username'];
    $guest->G_PASS = sha1($_POST['pass']);

    if ($guest->update($_SESSION['GUESTID'])) {
        echo "<script type='text/javascript'>window.location = '" . WEB_ROOT . "index.php';</script>";
    } else {
        echo "<p style='color: red;'>Error updating information. Please try again.</p>";
    }
}

if (isset($_POST['savephoto'])) {
    if (!isset($_FILES['image']['tmp_name']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        message("No Image Selected or Upload Error!", "error");
        redirect(WEB_ROOT . "index.php");
    } else {
        $file = $_FILES['image']['tmp_name'];
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $image_name = addslashes($_FILES['image']['name']);
        $image_size = getimagesize($_FILES['image']['tmp_name']);

        if ($image_size === FALSE) {
            message("That's not an image!");
            redirect(WEB_ROOT . "index.php");
        } else {
            $location = "guest/photos/" . $_FILES["image"]["name"];

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $location)) {
                $g = new Guest();
                $g->LOCATION = $location;
                if ($g->update($_SESSION['GUESTID'])) {
                    redirect(WEB_ROOT . "index.php");
                } else {
                    message("Failed to update guest information.");
                    redirect(WEB_ROOT . "index.php");
                }
            } else {
                message("Failed to move uploaded file.");
                redirect(WEB_ROOT . "index.php");
            }
        }
    }
}
?>
