<?php
session_start();
require "db.php";

if (!isset($_SESSION["user"])) {
    die("Not logged in.");
}

$user = $_SESSION["user"];
$gradesStore = getGradeStore();

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

// Filters
$filterSemester = $_GET["semester"] ?? "";
$startYear = $_GET["startYear"] ?? "";
$endYear = $_GET["endYear"] ?? "";
$sort = $_GET["sort"] ?? "";

// Fetch grades
$grades = $gradesStore->findBy(["student_id", "=", $user]);

// Filter by semester
if ($filterSemester) {
    $grades = array_filter($grades, function($g) use ($filterSemester) {
        return $g["semester"] === $filterSemester;
    });
}

// Filter by year range
if ($startYear && $endYear) {
    $grades = array_filter($grades, function($g) use ($startYear, $endYear) {
        return $g["year"] >= $startYear && $g["year"] <= $endYear;
    });
}

// Sorting
if ($sort) {
    usort($grades, function($a, $b) use ($sort) {
        return $a[$sort] <=> $b[$sort];
    });
}

// GPA calculations
$totalPoints = 0;
$totalCredits = 0;
$semesterStats = [];

foreach ($grades as $g) {
    $points = gradeToPoints($g["grade"]) * $g["credits"];

    $totalPoints += $points;
    $totalCredits += $g["credits"];

    $key = $g["semester"] . " " . $g["year"];

    if (!isset($semesterStats[$key])) {
        $semesterStats[$key] = ["points" => 0, "credits" => 0];
    }

    $semesterStats[$key]["points"] += $points;
    $semesterStats[$key]["credits"] += $g["credits"];
}

$cumulativeGPA = $totalCredits ? $totalPoints / $totalCredits : 0;
?>
