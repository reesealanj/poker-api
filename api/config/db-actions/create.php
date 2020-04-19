<?php
    include_once("../Database.php");

    $db = new Database();
    $connection = $db->getConnection();

    echo "DB Configured Sucessfully, you may now close this tab\n";
    
    $file = file_get_contents("db-setup.sql");
    $run = $connection->exec($file);
?>