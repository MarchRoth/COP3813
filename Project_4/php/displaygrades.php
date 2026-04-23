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

// Get sorting
$sort = $_GET["sort"] ?? "";

// Fetch grades
$grades = $gradesStore->findBy(["student_id", "=", $user]);

// Sorting
if ($sort) {
    usort($grades, function($a, $b) use ($sort) {
        return $a[$sort] <=> $b[$sort];
    });
}

// GPA calc
$totalPoints = 0;
$totalCredits = 0;

foreach ($grades as $g) {
    $totalPoints += gradeToPoints($g["grade"]) * $g["credits"];
    $totalCredits += $g["credits"];
}

$gpa = $totalCredits ? $totalPoints / $totalCredits : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Grade Viewer</title>
    <link rel="stylesheet" href="../css/mySite.css">
</head>
<body>

<h1>Grade View Selection</h1>

<!-- Sorting -->
<form method="GET">
    <label>Sort By:</label>

    <label>
        <input type="radio" name="sort" value="semester"> Semester
    </label>

    <label>
        <input type="radio" name="sort" value="year"> Year
    </label>

    <button type="submit">Apply</button>
</form>

<!-- Grades Table -->
<h2>Your Grades</h2>

<table border="1">
<tr>
    <th>Semester</th>
    <th>Year</th>
    <th>Course</th>
    <th>Credits</th>
    <th>Grade</th>
    <th>Instructor</th>
</tr>

<?php if ($grades): ?>
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
<tr><td colspan="6">No grades found</td></tr>
<?php endif; ?>

</table>

<h2>Cumulative GPA: <?= number_format($gpa, 2) ?></h2>

</body>
</html>