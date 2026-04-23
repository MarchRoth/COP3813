<?php
require "../vendor/autoload.php";

use SleekDB\Store;

$dataDir = __DIR__ . "/db"; //The directory containing each Store
$userStore = new Store("users", $dataDir); //The store of users
$gradeStore = new Store("grades", $dataDir); // The store of grades

// Users store
function getUserStore() {
    global $userStore;
    return $users = $userStore->findAll(); //Returns an array of all users Ex) [["student_id" => $id, "password" => $password],...]
}

// Grades store
function getGradeStore() {
    global $gradeStore;
    return $gradeStore->findAll();
}
?>