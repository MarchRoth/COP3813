<?php
session_start();
require "db.php";

if (!isset($_SESSION["user"])) {
    die("Not logged in.");
}

$user = $_SESSION["user"];
$gradesStore = getGradeStore();

// ----------------------
// GPA Helper Function
// ----------------------
function gradeToPoints($grade) {
    return [
        "A+"=>4.0,"A"=>4.0,"A-"=>3.7,
        "B+"=>3.3,"B"=>3.0,"B-"=>2.7,
        "C+"=>2.3,"C"=>2.0,"C-"=>1.7,
        "D+"=>1.3,"D"=>1.0,"D-"=>0.7,
        "F"=>0.0
    ][$grade];
}

// ----------------------
// Get Filters
// ----------------------
$filterSemester = $_GET["semester"] ?? "";
$startYear = $_GET["startYear"] ?? "";
$endYear = $_GET["endYear"] ?? "";
$sort = $_GET["sort"] ?? "";

// ----------------------
// Fetch Grades
// ----------------------
$query = $gradesStore->where("student_id", "=", $user);

if ($filterSemester) {
    $query = $query->where("semester", "=", $filterSemester);
}

$grades = $query->fetch();

// ----------------------
// Filter by Year Range
// ----------------------
if ($startYear && $endYear) {
    $grades = array_filter($grades, function($g) use ($startYear, $endYear) {
        return $g["year"] >= $startYear && $g["year"] <= $endYear;
    });
}

// ----------------------
// Sorting
// ----------------------
if ($sort) {
    usort($grades, function($a, $b) use ($sort) {
        return $a[$sort] <=> $b[$sort];
    });
}

// ----------------------
// GPA Calculations
// ----------------------
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

<!DOCTYPE html>
<html>
<head>
    <title>Grade Viewer</title>
    <style>
        body {
            font-family: Arial;
            margin: 20px;
        }

        h1, h2 {
            text-align: center;
        }

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        input, select, button {
            padding: 8px;
            margin: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        @media (max-width: 600px) {
            table, th, td {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

<h1>Grade Viewer</h1>

<!-- Filter Form -->
<form method="GET">
    <label>Semester:</label>
    <select name="semester">
        <option value="">All</option>
        <option value="Fall">Fall</option>
        <option value="Spring">Spring</option>
        <option value="Summer">Summer</option>
    </select>

    <label>Start Year:</label>
    <input type="number" name="startYear" placeholder="2020">

    <label>End Year:</label>
    <input type="number" name="endYear" placeholder="2026">

    <button type="submit">Apply Filters</button>
</form>

<!-- Sorting -->
<div style="text-align:center;">
    <button onclick="location.href='viewer.php?sort=semester'">Sort by Semester</button>
    <button onclick="location.href='viewer.php?sort=year'">Sort by Year</button>
</div>

<!-- Grades Table -->
<table>
<tr>
    <th>Semester</th>
    <th>Year</th>
    <th>Course</th>
    <th>Credits</th>
    <th>Grade</th>
    <th>Instructor</th>
</tr>

<?php if (count($grades) > 0): ?>
    <?php foreach ($grades as $g): ?>
        <tr>
            <td><?= htmlspecialchars($g["semester"]) ?></td>
            <td><?= htmlspecialchars($g["year"]) ?></td>
            <td><?= htmlspecialchars($g["course"]) ?></td>
            <td><?= htmlspecialchars($g["credits"]) ?></td>
            <td><?= htmlspecialchars($g["grade"]) ?></td>
            <td><?= htmlspecialchars($g["instructor"]) ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="6">No grades found.</td></tr>
<?php endif; ?>
</table>

<!-- GPA Section -->
<h2>Cumulative GPA: <?= number_format($cumulativeGPA, 2) ?></h2>

<h2>Semester GPAs</h2>
<table>
<tr>
    <th>Semester</th>
    <th>GPA</th>
</tr>

<?php foreach ($semesterStats as $sem => $data): 
    $gpa = $data["credits"] ? $data["points"] / $data["credits"] : 0;
?>
<tr>
    <td><?= $sem ?></td>
    <td><?= number_format($gpa, 2) ?></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>