<?php
require "../vendor/autoload.php";

use SleekDB\SleekDB;

$dataDir = __DIR__ . "/db";

// Users store
function getUserStore() {
    global $dataDir;
    return SleekDB::store("users", $dataDir);
}

// Grades store
function getGradeStore() {
    global $dataDir;
    return SleekDB::store("grades", $dataDir);
}
?>