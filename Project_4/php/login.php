<?php
session_start();
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["existingid"];
    $password = $_POST["existingpassword"];

    $users = getUserStore();

    $user = $users->where("student_id", "=", $id)->fetch();

    if ($user && password_verify($password, $user[0]["password"])) {
        $_SESSION["user"] = $id;
        header("Location: action.html");
        exit();
    }

    echo "Invalid login.";
}
?>