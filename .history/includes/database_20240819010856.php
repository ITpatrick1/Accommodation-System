<?php
require_once(LIB_PATH.DS."config.php");

class Database {
    var $sql_string = '';
    var $error_no = 0;
    var $error_msg = '';
    private $conn;
    public $last_query;
    
    function __construct() {
        $this->open_connection();
    }
    
    public function open_connection() {
        $this->conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
        if (!$this->conn) {
            echo "Problem in database connection! Contact administrator!";
            exit();
        } else {
            $db_select = mysqli_select_db($this->conn, DB_NAME);
            if (!$db_select) {
                echo "Problem in selecting database! Contact administrator!";
                exit();
            }
        }
    }
    
    function setQuery($sql='') {
        $this->sql_string = $sql;
    }
    
    function executeQuery() {
        $result = mysqli_query($this->conn, $this->sql_string);
        $this->confirm_query($result);
        return $result;
    }    
    
    private function confirm_query($result) {
        if (!$result) {
            $this->error_no = mysqli_errno($this->conn);
            $this->error_msg = mysqli_error($this->conn);
            return false;                
        }
        return $result;
    } 
    
    function loadResultList($key='') {
        $cur = $this->executeQuery();
        $array = array();
        while ($row = mysqli_fetch_object($cur)) {
            if ($key) {
                $array[$row->$key] = $row;
            } else {
                $array[] = $row;
            }
        }
        mysqli_free_result($cur);
        return $array;
    }
    
    function loadSingleResult() {
        $cur = $this->executeQuery();
        $data = null;
        while ($row = mysqli_fetch_object($cur)) {
            $data = $row;
        }
        mysqli_free_result($cur);
        return $data;
    }
    
    function getFieldsOnOneTable($tbl_name) {
        $this->setQuery("DESC ".$tbl_name);
        $rows = $this->loadResultList();
        $fields = array();
        foreach ($rows as $row) {
            $fields[] = $row->Field;
        }
        return $fields;
    }    

    public function fetch_array($result) {
        return mysqli_fetch_array($result);
    }
    
    public function num_rows($result_set) {
        return mysqli_num_rows($result_set);
    }
  
    public function insert_id() {
        return mysqli_insert_id($this->conn);
    }
  
    public function affected_rows() {
        return mysqli_affected_rows($this->conn);
    }
    
    public function escape_value($value) {
        return mysqli_real_escape_string($this->conn, $value);
    }
    
    public function close_connection() {
        if (isset($this->conn)) {
            mysqli_close($this->conn);
            unset($this->conn);
        }
    }
}

$mydb = new Database();
?>
