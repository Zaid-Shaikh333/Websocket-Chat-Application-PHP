<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
//require("../database/connection.php");


class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        //include('../functions.php');

        $getToken = $conn->httpRequest->getUri()->getQuery();

        parse_str($getToken,$setToken);
        //update_connection_id($conn->resourceId,$setToken);
        $token = $setToken['token'];
        echo "New connection! ({$conn->resourceId})\n";
        
        $connection = mysqli_connect("localhost","root","","chat");
        $user_connection_id = $conn->resourceId;
        $query = "UPDATE chat_user SET user_connection_id = '$user_connection_id' WHERE user_token = '$token'";
        $execute = mysqli_query($connection,$query);


        //echo $setToken"\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg,true);

        if($data['type'] == 'Private')
        {

            $sender = $data['sender_email'];
            $receiver = $data['receiver_email'];
            $message = $data['msg'];

            date_default_timezone_set("Asia/Kolkata");  // Set Indian Time Zone as Default
            $time = date('Y-m-d h:i:s');
            $data['time'] = $time;
            // Now time to Encrypt the Message
            /*
            $secret_key = md5((bin2hex($sender));
            $encryption_method = "aes-128-ctr";
            $initialization_vector_length = openssl_cipher_iv_length($encryption_method);
            $initialization_vector = openssl_random_psuedo_bytes($initialization_vector_length);
            $encrypted_message = openssl_encrypt($message,$encryption_method,$secret_key,0,$initialization_vector);
            */
            $connection = mysqli_connect("localhost","root","","chat"); // Set Database Connection
            $query = "INSERT INTO chat_messages(message,timestamp,to_user_email,from_user_email)
            VALUES('$message','$time','$receiver','$sender')"; // Insert Chat messages into Database
            $execute1 = mysqli_query($connection,$query);

            $get_connection_id = "SELECT user_connection_id FROM chat_user WHERE user_email = '$receiver'";
            $execute2 = mysqli_query($connection,$get_connection_id);
            $fetch_connection_id = mysqli_fetch_assoc($execute2);
            $receiver_connection_id = $fetch_connection_id['user_connection_id'];

            foreach ($this->clients as $client) {
                /*if ($from !== $client) {
                    // The sender is not the receiver, send to each client connected
                    $client->send($msg);
                }*/
                if($client->resourceId == $receiver_connection_id || $from == $client)
                {
                    $data['from'] = $data['sender_name'];
                    $client->send(json_encode($data));
                }

            }
        }

        else
        {
            $data['date'] = date( 'd-m-Y h:i:s');
            $data['msg'] = base64_encode($data['msg']);
            foreach ($this->clients as $client) {
                /*if ($from !== $client) {
                    // The sender is not the receiver, send to each client connected
                    $client->send($msg);
                }*/
                $data['from'] = ($from == $client)? 'Me': 'Other';

                $client->send(json_encode($data));

            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
?>