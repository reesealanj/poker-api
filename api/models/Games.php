<?php
class Games {
    // DB Connection
    private $conn;
    private $table_name = "games";

    // Games Fields
    public $game_id;
    public $created_by;
    public $state; 
    public $scanned_cards;
    public $comm_1;
    public $comm_2;
    public $comm_3;
    public $comm_4;
    public $comm_5;
    public $hand_1;
    public $hand_2;
    public $score;
    public $odds;
    public $avg_score;

    // Object constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . "
                    SET game_id=:game_id, created_by=:created_by, state=1";
        
        try {
            $stmt = $this->conn->prepare($query);

            if($stmt) {
                $this->game_id = $this->generateID(); 

                $stmt->bindParam(":game_id", $this->game_id);
                $stmt->bindParam(":created_by", $this->created_by);
            }

            $result = $stmt->execute();

            if($result) {
                return game_id;
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

    function validateGameState($game_id) {
        $query = "SELECT * FROM " . $this->table_name . "
                    WHERE game_id=:game_id";
        
        try {
            $stmt = $this->conn->prepare($query);

            if($stmt) {
                $game_id = $this->sanitize($game_id);
                $stmt->bindParam(":game_id", $game_id);
            }

            $result = $stmt->execute();
            if($result) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row['is_active'] == 1) {
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

    function generateID() {
        $length = 10;
        $cstrong = true;

        $bytes = openssl_random_pseudo_bytes($length, $cstrong);
        $hex = bin2hex($bytes);

        return $hex;
    }

    function sanitize($input) {
        return htmlspecialchars(strip_tags($input)); 
    }
}
