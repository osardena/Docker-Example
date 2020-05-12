<?php

require 'vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Exception\AMQPIOException;

$messaging_attempt = 3;

// Attempts connection to messaging service
while($messaging_attempt > 0){
    try{
        sleep(2); // Wait 2 seconds before connection attempt
        $connection = new AMQPConnection('messaging', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('login_queue', false, false, false, false);
        $messaging_attempt = 0; // Set to 0 to exit the while loop after connection
        echo " [*] Waiting for messages. To exit press CTRL+C\n";
    }
    catch (AMQPIOException $e){
        // Print out error messages
        echo "Connection Failed: " . $e->getMessage() . "\n";
        $messaging_attempt--;
        // Wait 3 seconds before connection attempt
        sleep(3);
    }
}

$callback = function ($msg) {

    echo '[x] Message Received ', "\n";

    $data = json_decode($msg->body,true);
    $email = $data['email'];
    $password = $data['password'];
    $action = $data['action'];

    switch ($action){
        case 'login':
            echo "LOGIN TEST WORKED\n";
            // Automatically resolve to the docker service named 'database'
            $servername = "database";
            $database = "test";
            $username = "root";
            $password = "guest";

// Limit to 3 attempts
            $attempt = 3;

// Attempt connection to database
            while ($attempt>0) {
                try {
                    // Wait 2 seconds before connection attempt
                    sleep(2);
                    $connection = new PDO("mysql:host=$servername;dbname=test",$username,$password);

                    // Set PDO error mode to exception
                    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    //  Set attempt to 0 in order to exit the while loop after establishing a connection
                    $attempt = 0;
                    echo "Connection Successful\n";
                }
                catch (PDOException $e) {
                    // Print out error messages
                    echo "Connection Failed: " . $e->getMessage() . "\n";
                    $attempt--;
                    // Wait 2 seconds before connection attempt
                    sleep(2);
                }
            }

//$connection = null; // Closes the connection automatically when the program ends
//$query = "INSERT INTO users (user_id,name,phone_number) VALUES ('3','joe','9333339345')";
//$connection->exec($query);
            break;
        default:
            echo "NO VALUE\n";
            break;
    }
    echo "[x] Message Sent","\n";
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};
// $channel->basic_qos(null, 1, null);
$channel->basic_consume('login_queue', '', false, false, false, false, $callback);

while(count($channel->callbacks)){ // Every time a message is received a defined callback will be executed
    $channel->wait();
}