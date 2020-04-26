<?php 

    if (isset($_POST['login-submit'])) {
        require_once "Database.php";
        session_start();

        $username = $_POST['username'];
        $password = $_POST['pwd'];

        if (empty($username) || empty($password)) {
            header("Location: ../index.php?e=1");
            exit();
        }

        $db = new Database();
        $conn = $db->getConnection();

        $query = "SELECT * FROM users WHERE username=:username";

        try {
            $stmt = $conn->prepare($query);

            if ($stmt) {

                $username = htmlspecialchars(strip_tags($username));
                $stmt->bindParam(":username", $username);
            }

            $result = $stmt->execute();

            if ($result) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $hash = $row['password'];

                if (password_verify($password, $hash)) {
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['api_key'] = $row['api_key'];
                    $_SESSION['user_id'] = $row['user_id'];
                    header("Location: ../home/index.php");
                    exit(); 
                } else {
                    header("Location: ../index.php?e=2");
                    exit();
                }
            } else {
                header("Location: ../index.php?e=3");
                exit();
            }
        } catch (PDOException $e) {
            echo "DB Problem: " . $e->getMessage();
            exit(); 
        }
    } else {
        header("Location: ../index.php");
        exit();
    }