<?php
require "db.php"; #requires the db.php file to be created within the same directory and used.

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["newid"];
    $password = $_POST["createpassword"];

    $users = getUserStore();

    
    // Check duplicate user
    $existing = $users->findBy(["student_id", "=", $id]);

    if ($existing) {
        header("Location: ../html/index.html");
    }
    

    $users->insert([
        "student_id" => $id,
        "password" => password_hash($password, PASSWORD_DEFAULT)
    ]);

    header("Location: ../html/user.html");

    }
?>