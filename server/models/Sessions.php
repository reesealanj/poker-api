<?php
class Sessions {

    private $conn;
    private $table_name = "sessions";
    private $user_table = "users";

    public $session_id;
    public $created_at;
    public $created_by;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }

    function start() {
        // if created_by user has active session prevent them from making a new one.
        if($this->checkUserSessionState()){
            return false;
        }
        $query = "INSERT INTO " . $this->table_name . "
                    SET session_id=:session_id,created_at=NOW(),created_by=:user_id,active=1";

        try {
            $stmt = $this->conn->prepare($query);

            if($stmt) {
                $this->session_id = $this->generateSessionID();

                $stmt->bindParam(":session_id", $this->session_id);
                $stmt->bindParam(":user_id", $this->created_by);
            }

            $result = $stmt->execute();

            if($result) {
                return true;
            } else {
                $error = $stmt->errorInfo();
                echo "Query Failed: " . $error[2] . "\n";
                return false;
            }
        } catch (PDOException $e) {
            echo "DB Problem: " . $e->getMessage();
        }
    }

    // Generate a cryptographically secure session id
    function generateSessionID() {
        $length = 10;
        $cstrong = true;

        $bytes = openssl_random_pseudo_bytes($length, $cstrong);
        $hex = bin2hex($bytes);

        return $hex;
    }
    // check whether current user (created_by) has an active session
    function checkUserSessionState() {
        $query = "SELECT * FROM " . $this->user_table . " WHERE id=:user_id";

        try {
            $stmt = $this->conn->prepare($query);

            if($stmt) {
                $this->created_by = $this->sanitize($this->created_by);
                $stmt->bindParam(":user_id", $this->created_by);
            }

            $result = $stmt->execute();
            if($result) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return $row['session_active'];
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "DB Problem: " . $e->getMessage();
        }
    }

    // check whether a session is active
    public function validateSessionState($session_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE session_id=:session_id";

        try {
            $stmt = $this->conn->prepare($query);

            if($stmt) {
                $session_id = $this->sanitize($session_id);
                $stmt->bindParam(":session_id", $session_id); 
            }

            $result = $stmt->execute();
            if($result) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row['active'] == 1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $error = $stmt->errorInfo();
                echo "Query Failed: " . $error[2] . "\n";
                return false;
            }
        } catch (PDOException $e) {
            echo "DB Problem: " . $e->getMessage();
            return false;
        }
    }

    public function authValidateSession($session, $password) {
        /* 1. Check whether session exists
        *  2. Check whether session is active
        *  3. Check which user session is bound to
        *  4. verify that the user bound to session has a password of $password
        */

        $query = "SELECT * FROM " . $this->table_name . " WHERE session_id=:session_id";

        try {
            $stmt = $this->conn->prepare($query);

            if($stmt) {
                $sess_input = $this->sanitize($session);
                $stmt->bindParam(":session_id", $sess_input);
            }

            $result = $stmt->execute();
            if($result) {
                $rows = $stmt->rowCount();
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if($rows > 0) {
                    $user_id = $data['created_by'];
                    $query = "SELECT * FROM " . $this->user_table . " WHERE id=:user_id";
                    try{
                        $stmt = $this->conn->prepare($query);
                        if($stmt) {
                            $stmt->bindParam(":user_id", $user_id);
                        }
                        $result = $stmt->execute();
                        if($result) {
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $pw_hash = $row['password'];
                            if(password_verify($password, $pw_hash)){
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
                        return false;
                    }
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

    public function status($reqForm) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE session_id=:session_id";
        
        $stmt = $this->conn->prepare($query);

        try {
            if($stmt) {
                $stmt->bindParam(":session_id", $this->session_id);
            }

            $result = $stmt->execute(); 
            if($result) {
                
            }
        } catch (PDOException $e) {

        }
        return array("form" => $reqForm);
    }
    
    function sanitize($input) {
        return htmlspecialchars(strip_tags($input));
    }
}
