<?php
require "../vendor/autoload.php";

use SleekDB\SleekDB;

$dataDir = __DIR__ . "/db";

$store = SleekDB::store("test", $dataDir);

// Insert test data
$store->insert([
    "name" => "Ej",
    "test" => "SleekDB is working"
]);

// Fetch data
$results = $store->fetch();

echo "<pre>";
print_r($results);
echo "</pre>";
?>