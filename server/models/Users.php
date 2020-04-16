<?php

class Users {
    // DB Connection Information
    private $conn; 
    private $table_name = "users";
    // Object Fields
    public $user_id;
    public $username;
    public $password;
    public $api_key;
    
    // Object Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                    SET username=:username,password=:password,api_key=:api_key";

        try {
            $stmt = $this->conn->prepare($query);

            if($stmt) {
                // $this->sanitize inputs to be safe
                $this->username=$this->sanitize($this->username);
                $this->password=$this->sanitize($this->password);
                $this->api_key=$this->generateKey();
    
                // Bind values to prepared 
                $stmt->bindParam(":username", $this->username);
                $stmt->bindParam(":password", $this->password);
                $stmt->bindParam(":api_key", $this->api_key);
            }

            $result = $stmt->execute();

            if($result) {
                return true;
            } else {
                $error = $stmt->errorInfo();
                echo "Query Failed: " . $error[2] . "\n";
                return false;
            }
        } catch (PDOException $e){
            echo "DB Problem: " . $e->getMessage();
        }
    }

    function sanitize($input) {
        return htmlspecialchars(strip_tags($input));
    }

    function generateKey() {
        $length = 32;
        $cstrong = true;

        $bytes = openssl_random_pseudo_bytes($length, $cstrong);
        $hex = bin2hex($bytes);

        return $hex;
    }

    function checkUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username=:username";

        try {
            $stmt = $this->conn->prepare($query);

            if($stmt) {
                $username = $this->sanitize($username);
                $stmt->bindParam(":username", $username);
            }

            $result = $stmt->execute();

            if($result) {
                $rows = $stmt->rowCount();
                if($rows > 0) {
                    return true;
                } 
                return false;
            } else {
                $error = $stmt->errorInfo();
                echo "Query Failed: " . $error[2] . "\n";
                return false;
            }
        } catch (PDOException $e) {
            echo "DB Problem: " . $e->getMessage();
        }
    }   
}

?>