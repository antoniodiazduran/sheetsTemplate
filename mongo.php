<?php

require 'vendor/autoload.php';

$client = new MongoDB\Client(
    'mongodb+srv://user:pass@cluster0.zqpgfah.mongodb.net/myFirstDatabase?serverSelectionTryOnce=false&serverSelectionTimeoutMS=15000');

$collection = $client->demo->beers;

//  $result = $collection->insertOne( [ 'name' => 'Pacifico', 'brewery' => 'Modelo' ] );
//  echo "Inserted with Object ID '{$result->getInsertedId()}'";

//$result = $collection->find( [ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ] );
$result = $collection->find();

foreach ($result as $entry) {
    echo $entry['_id'], ': ', $entry['name'], ': ', $entry['brewery'], "\n";
}
?>
