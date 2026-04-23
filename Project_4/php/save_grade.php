<?php
session_start();
require "db.php";

if (!isset($_SESSION["user"])) {
    die("Not logged in.");
}

$grades = getGradeStore();

$user = $_SESSION["user"];

$data = [
    "student_id" => $user,
    "semester" => $_POST["semester"],
    "year" => $_POST["year"],
    "course" => $_POST["courseid"],
    "credits" => (int)$_POST["credithours"],
    "grade" => $_POST["grades"],
    "instructor" => $_POST["instructor"]
];

//  Duplicate check
$duplicate = $grades
    ->where("student_id", "=", $user)
    ->where("semester", "=", $data["semester"])
    ->where("year", "=", $data["year"])
    ->where("course", "=", $data["course"])
    ->fetch();

if ($duplicate) {
    echo "Duplicate course entry!";
    exit();
}

$grades->insert($data);

echo "Grade saved!";
?>