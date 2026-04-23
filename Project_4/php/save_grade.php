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
    "credits" => $_POST["credithours"], //[..., "credits" => $credithours]
    "grade" => $_POST["grades"],
    "instructor" => $_POST["instructor"]
];

//  Duplicate check
$duplicate = $grades->findBy([
    ["student_id", "=", $user],
    ["semester", "=", $data["semester"]],
    ["year", "=", $data["year"]],
    ["course", "=", $data["course"]]
]);

if ($duplicate) {
    echo "Duplicate course entry!";
    exit();
}

$grades->insert($data);

echo "Grade saved!";
?>