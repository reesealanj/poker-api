<?php

class Users {

    // DB Connection Information
    private $conn;
    private $table_name = "users";

    // User Object Fields  
    public $id;
    public $username;
    public $password;
    public $session_active;
    public $session_id;

    // Users Object Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    function create() {
        $query = "INSERT INTO " 
                    . $this->table_name . " 
                  SET 
                    username=:username, password=:password, session_active=:session_active, session_id=:session_id";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs to be safe
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->session_active=htmlspecialchars(strip_tags($this->session_active));
        $this->session_id=htmlspecialchars(strip_tags($this->session_id));

        // Bind values to prepared 
        $stmt->bindParam(":username", $this->name);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":session_active",  $this->session_active);
        $stmt->bindParam(":session_id", $this->session_id);

        if($stmt->execute()){
            return true;
        } 

        return false;
    }
}

?>