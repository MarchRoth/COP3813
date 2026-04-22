<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["newid"];
    $password = $_POST["createpassword"];

    $users = getUserStore();

    // Check duplicate user
    $existing = $users->where("student_id", "=", $id)->fetch();

    if ($existing) {
        echo "User already exists.";
        exit();
    }

    $users->insert([
        "student_id" => $id,
        "password" => password_hash($password, PASSWORD_DEFAULT)
    ]);

    echo "Account created!";
}
?>