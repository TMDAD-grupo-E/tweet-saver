<?php
include(__DIR__ . '/config.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;

$exchange = 'twitter_fanout';
$queue = 'twitter_fanout';
$consumerTag = 'consumer' . getmypid();

$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
$channel = $connection->channel();

$channel->queue_declare($queue, false, false, false, true);

$channel->exchange_declare($exchange, 'fanout', false, true, false);

$channel->queue_bind($queue, $exchange);

function process_message($message)
{
var_dump($message);	
$manager = new MongoDB\Driver\Manager('mongodb://database-tmdad.westeurope.cloudapp.azure.com:27017');
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert([$message]);
$manager->executeBulkWrite('tmdad.tweets', $bulk);
}


$channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');

function shutdown($channel, $connection)
{
	    $channel->close();
	        $connection->close();
}

register_shutdown_function('shutdown', $channel, $connection);

while (count($channel->callbacks)) {
	    $channel->wait();
}
?>
