<?php
require 'vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('hello', false, false, false, false);

$msg = new AMQPMessage($_GET['send']);
$channel->basic_publish($msg, '', 'hello');

echo " [x] message Send'\n";

$channel->close();
$connection->close();