<?php
$manager = new MongoDB\Driver\Manager('mongodb://database-tmdad.westeurope.cloudapp.azure.com:27017');
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert(['x' => 1]);
$bulk->insert(['x' => 2]);
$bulk->insert(['x' => 3]);
$manager->executeBulkWrite('tmdad.tweets', $bulk);

?>
