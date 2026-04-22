<?php
session_start();
require "db.php";

if (!isset($_SESSION["user"])) {
    die("Not logged in.");
}

$user = $_SESSION["user"];
$gradesStore = getGradeStore();

// Fetch user grades
$grades = $gradesStore
    ->where("student_id", "=", $user)
    ->fetch();

// GPA helper
function gradeToPoints($grade) {
    return [
        "A+"=>4.0,"A"=>4.0,"A-"=>3.7,
        "B+"=>3.3,"B"=>3.0,"B-"=>2.7,
        "C+"=>2.3,"C"=>2.0,"C-"=>1.7,
        "D+"=>1.3,"D"=>1.0,"D-"=>0.7,
        "F"=>0.0
    ][$grade];
}

$totalPoints = 0;
$totalCredits = 0;

echo "<table border='1'>
<tr>
<th>Semester</th><th>Year</th><th>Course</th>
<th>Credits</th><th>Grade</th><th>Instructor</th>
</tr>";

foreach ($grades as $g) {

    $points = gradeToPoints($g["grade"]) * $g["credits"];
    $totalPoints += $points;
    $totalCredits += $g["credits"];

    echo "<tr>
        <td>{$g["semester"]}</td>
        <td>{$g["year"]}</td>
        <td>{$g["course"]}</td>
        <td>{$g["credits"]}</td>
        <td>{$g["grade"]}</td>
        <td>{$g["instructor"]}</td>
    </tr>";
}

echo "</table>";

$gpa = $totalCredits ? $totalPoints / $totalCredits : 0;

echo "<h2>Cumulative GPA: " . number_format($gpa, 2) . "</h2>";
?>