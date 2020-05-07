<?php

require '/var/www/html/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Create connection to messaging service
$connection = new AMQPConnection('messaging', 5672, 'guest', 'guest');

$channel = $connection->channel();

// Declare a queue
$channel->queue_declare('login_queue', false, false, false, false);

$data = json_encode($_POST);

$msg = new AMQPMessage($data,array('delivery_mode' => 2));

$channel->basic_publish($msg, '', 'login_queue');