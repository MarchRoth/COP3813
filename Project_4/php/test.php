<?php
require "../vendor/autoload.php";

use SleekDB\Store;

$dataDir = __DIR__ . "/db";

$store = new Store("test", $dataDir);

// Insert test data
$store->insert([
    "name" => "Marcus",
    "test" => "SleekDB is working"
]);

// Fetches all data in the store and returns an array containing each 'document'
$resultArray = $store->findAll(); //[["name" => "Marcus", "test" => "SleekDB is working"]]

foreach($resultArray as $user => ["name" => $name, "test" => $testMsg]) {
    echo $name . " " . $testMsg; //Outputs: "Marcus SleekDB is working"
}


?>