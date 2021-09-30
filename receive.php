<?php
require 'vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    $mail = new PHPMailer(true);
    
        echo ' [x]', $msg->body, "\n";

        $mail->SMTPDebug = 0;                                       // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'catalogowebsystemgcmx@gmail.com';    // SMTP username
        $mail->Password   = 'scorpions.,,';                         // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('catalogowebsystemgcmx@gmail.com', '@TestRabbitMQ');
        $mail->addAddress('jorgegcmx@gmail.com');                               // Add a recipient
        $mail->addReplyTo('catalogowebsystemgcmx@gmail.com', '@TestRabbitMQ');


        // Content
        $mail->isHTML(true);                                           // Set email format to HTML
        $mail->Subject = 'Test rabbitMQ';
        $mail->Body    = $msg->body;
        $mail->AltBody = 'Test';

        $mail->send();
        //echo 'Message has been sent';
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();